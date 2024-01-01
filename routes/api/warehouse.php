<?php

use App\Http\Controllers\NotificationController;
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
Route::post('warehouse/register', [AuthController::class, 'warehouseRegister']);
Route::post('warehouse/login', [AuthController::class, 'warehouseLogin']);
Route::group(['prefix' => 'warehouse', 'middleware' => ['auth:warehouse-api', 'scopes:warehouse']], function () {
    // authenticated staff routes here
    Route::post('logout', [AuthController::class, 'warehouseLogout']);
    // get all medicines
    Route::get('/showAllMedicines', [DrugsController::class, 'showAllMedicines']);
    // store medicines
    Route::post('/store', [DrugsController::class, 'store']);
    // show medicines in details
    Route::get('/showDetails/{id}', [DrugsController::class, 'showDetails']);
    // return all Orders
    Route::get('/showOrders', [OrderController::class, 'showOrders']);
    // return a specific order by id
    Route::get('/showOrder/{order_id}', [OrderController::class, 'showOrder']);
    Route::post('/updateStatus/{order_id}', [OrderController::class, 'updateStatus']);
    // favorites
    Route::post('/addfavorites', [DrugsController::class, 'addfavorites']);
    Route::get('/favorites', [DrugsController::class, 'favorites']);
    Route::delete('/desroyfavorites', [DrugsController::class, 'desroyfavorites']);

    Route::get('/getNotifications', [NotificationController::class, 'getNotifications']);
    Route::put('/readNotification/{id}', [NotificationController::class, 'readNotificationWarehouse']);
    Route::put('/markAllAsReadWarehouse', [NotificationController::class, 'markAllAsReadWarehouse']);

});
