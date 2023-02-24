<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Ram;
use App\Models\Hdd;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controller for the Product Model
 */
class ProductController extends Controller
{
    public function __construct(private Product $model = new Product())
    {
        parent::__construct($model);
    }

    /**
     * List all the Products
     *
     * Provides filtering via request parameters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $request->validate([
            'model'         => 'sometimes|string',
            'ram_id'        => 'sometimes|exists:ram,id',
            'hdd_id'        => 'sometimes|exists:hdd,id',
            'location_id'   => 'sometimes|exists:locations,id',
            'storage_min'   => 'sometimes|numeric|lt:storage_max',
            'storage_max'   => 'sometimes|numeric|gt:storage_min',
            'sort_by'       => 'sometimes|in:model,price,storage',
            'sort_dir'      => 'sometimes|in:asc,desc',
        ]);

        $query = $this->model->query();
        if ($request['model']) {
            $query->where('model', 'LIKE', '%'.$request['model'].'%');
        }
        if ($request['ram_id']) {
            $query->where('ram_id', $request['ram_id']);
        }
        if ($request['hdd_id']) {
            $query->where('hdd_id', $request['hdd_id']);
        }
        if ($request['location_id']) {
            $query->where('location_id', $request['location_id']);
        }
        if ($request['storage_min'] && $request['storage_max']) {
            $query->whereBetween('storage', [$request['storage_min'], $request['storage_max']]);
        } else {
            if ($request['storage_min']) {
                $query->where('storage', '>', $request['storage_min']);
            }
            if ($request['storage_min']) {
                $query->where('storage', '<', $request['storage_max']);
            }
        }

        $sort_by = $request['sort_by'] ?? 'id';
        $sort_dir = $request['sort_dir'] ?? 'asc';

        return new JsonResponse(
            $query->orderBy($sort_by, $sort_dir)->get()
        );
    }

    /**
     * Create a new Product
     * Lookup values for RAM, HDD & Location need to be created already
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        // any lookup values need to be setup before inserting
        $request->validate([
            'model'         => 'required|string',
            'storage'       => 'required|numeric',
            'currency'      => 'required|in:$,£,€',
            'price'         => 'required|decimal:0,2',
            'ram_id'        => 'required|exists:ram,id',
            'hdd_id'        => 'required|exists:hdd,id',
            'location_id'   => 'required|exists:locations,id',
        ]);

        return parent::store($request);
    }

    /**
     * Drops all Products records and renews from spreadsheet
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request) : JsonResponse
    {
        request()->validate([
            'products' => 'required|mimes:xlx,xls,xlsx,csv|max:2048'
        ]);

        DB::beginTransaction();
        set_time_limit(0);
        // first clear down all existing data
        Product::query()->delete();
        Ram::query()->delete();
        Hdd::query()->delete();
        Location::query()->delete();

        Excel::import(new ProductsImport, $request->file('products'));

        DB::commit();
        $count = Product::query()->count();
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully imported products. Totalling '.$count,
        ]);
    }
}
