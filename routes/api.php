<?php

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


Route::post('warehouse/register',[AuthController::class, 'wareHouseRegister']);
Route::post('warehouse/login',[AuthController::class, 'warehouseLogin']);
Route::group( ['prefix' => 'warehouse','middleware' => ['auth:warehouse-api','scopes:warehouse'] ],function(){
    // authenticated staff routes here
    Route::post('/store', [storeController::class, 'store']);
    Route::post('logout',[AuthController::class, 'warehouseLogout']);
});
