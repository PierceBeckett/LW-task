<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller for the Product Model
 */
class ProductController extends Controller
{
    //

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
			'ram_id'		=> 'sometimes|exists:ram,id',
			'hdd_id'		=> 'sometimes|exists:hdd,id',
			'location_id'	=> 'sometimes|exists:locations,id',
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
		// the static method will return an array detailing its success, and meta info
		return new JsonResponse(Product::import());
	}
}
