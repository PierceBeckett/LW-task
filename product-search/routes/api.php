<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// basic product crud operations
Route::get('product', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show'])
    ->where(['id' => '[0-9]+']);
Route::post('product', [ProductController::class, 'store']);
Route::put('product/{id}', [ProductController::class, 'update'])
    ->where(['id' => '[0-9]+']);
Route::delete('product/{id}', [ProductController::class, 'destroy'])
    ->where(['id' => '[0-9]+']);

// product import from excel
Route::get('product/import', [ProductController::class, 'import']);

// lookup table crud

Route::get('ram', [RamController::class, 'index']);
Route::get('ram/{id}', [RamController::class, 'show'])
    ->where(['id' => '[0-9]+']);
Route::post('ram', [RamController::class, 'store']);
Route::put('ram/{id}', [RamController::class, 'update'])
    ->where(['id' => '[0-9]+']);
Route::delete('ram/{id}', [RamController::class, 'destroy'])
    ->where(['id' => '[0-9]+']);


Route::get('hdd', [HddController::class, 'index']);
Route::get('hdd/{id}', [HddController::class, 'show'])
    ->where(['id' => '[0-9]+']);
Route::post('hdd', [HddController::class, 'store']);
Route::put('hdd/{id}', [HddController::class, 'update'])
    ->where(['id' => '[0-9]+']);
Route::delete('hdd/{id}', [HddController::class, 'destroy'])
    ->where(['id' => '[0-9]+']);


Route::get('location', [LocationController::class, 'index']);
Route::get('location/{id}', [LocationController::class, 'show'])
    ->where(['id' => '[0-9]+']);
Route::post('location', [LocationController::class, 'store']);
Route::put('location/{id}', [LocationController::class, 'update'])
    ->where(['id' => '[0-9]+']);
Route::delete('location/{id}', [LocationController::class, 'destroy'])
    ->where(['id' => '[0-9]+']);
