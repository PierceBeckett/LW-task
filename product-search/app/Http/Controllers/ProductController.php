<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Ram;
use App\Models\Hdd;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'currency'      => 'required|in:$,€,£', // eventually replace with proper enum
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

        // first clear down all existing data
        Product::query()->delete();
        Ram::query()->delete();
        Hdd::query()->delete();
        Location::query()->delete();

        Excel::import(new ProductsImport, $request->file('products'));

		$count = Product::query()->count();
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully imported products. Totalling '.$count,
        ]);
    }
}
