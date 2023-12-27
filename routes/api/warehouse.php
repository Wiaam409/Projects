
<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\drugsController;
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
Route::post('warehouse/register',[AuthController::class, 'warehouseRegister']);
Route::post('warehouse/login',[AuthController::class, 'warehouseLogin']);
Route::group( ['prefix' => 'warehouse','middleware' => ['auth:warehouse-api','scopes:warehouse'] ],function(){
    // authenticated staff routes here
    Route::get('/showAllMedicines', [DrugsController::class, 'showAllMedicines']);
    Route::post('/store', [DrugsController::class, 'store']);
    Route::get('/showDetails/{id}', [DrugsController::class, 'showDetails']);
    Route::post('logout',[AuthController::class, 'warehouseLogout']);
    Route::get('/showOrders', [OrderController::class, 'showOrders']);
    Route::get('/showOrder/{order_id}', [OrderController::class, 'showOrder']);
    Route::post('/updateStatus/{order_id}', [OrderController::class, 'updateStatus']);
});
