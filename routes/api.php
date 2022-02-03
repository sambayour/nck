<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

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

// auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::group(['middleware' => ['jwt.verify']], function() {

    //logout
    Route::post('logout', [AuthController::class, 'logout']);

    //get user
    Route::get('user', [AuthController::class, 'get_user']);
    
    //cart
    Route::post('cart', [CartController::class, 'store']);

    //CRUD inventory endpoint
    Route::apiResource('inventories', InventoryController::class);

});
