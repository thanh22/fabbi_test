<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiRestaurantController;

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

Route::group([
    'namespace' => 'App\Http\Controllers\Api'
],
    function () {
        Route::get('meal', [ApiRestaurantController::class, 'getMeal']);
        Route::get('restaurant/{meal}', [ApiRestaurantController::class, 'getRestaurants']);
        Route::post('dishes', [ApiRestaurantController::class, 'getDishes']);
        Route::post('create', [ApiRestaurantController::class, 'create']);
    }
);
