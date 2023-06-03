<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ModelCarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResources([
    'brands' => BrandController::class,
    'models' => ModelCarController::class,
    'cars' => CarController::class,
]);

Route::get('/getAllBrands', [BrandController::class, 'getAllBrands']);
Route::get('/models-by-brand/{id}', [ModelCarController::class, 'getBrandsById']);
Route::post('/edtCar/{id}', [CarController::class, 'update']);
Route::delete('/deleteCarImage/{id}', [CarController::class, 'deleteCarImage']);

