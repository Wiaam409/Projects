<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\drugsController;
// use App\Http\Controllers\pharmacyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use \App\Http\Controllers\NotificationController;

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
Route::post('user/register', [AuthController::class, 'userRegister']);
Route::post('user/login', [AuthController::class, 'userLogin']);
Route::group(['prefix' => 'user', 'middleware' => ['auth:user-api', 'scopes:user']], function () {
    // Auth
    Route::post('logout', [AuthController::class, 'userLogout']);
    // show medicines in categories
    Route::get('/showCategories/{id}', [DrugsController::class, 'showCategories']);
    // search for a medicine
    Route::post('/search', [DrugsController::class, 'search']);
    // show medicine in details
    Route::get('/showDetails/{id}', [DrugsController::class, 'showDetails']);
    Route::get('/showOrder/{id}', [OrderController::class, 'showOrder']);
    // Orders
    Route::post('/makeOrder', [OrderController::class, 'makeOrder']);
    Route::get('/statusOrder', [OrderController::class, 'statusOrder']);
    // favorites
    Route::post('/addfavorites', [FavoritesController::class, 'addfavorites']);
    Route::get('/favorites', [FavoritesController::class, 'favorites']);
    Route::delete('/desroyfavorites', [FavoritesController::class, 'desroyfavorites']);
    // get user's notifications
    Route::get('/getNotifications', [NotificationController::class, 'getNotifications']);
    Route::put('/readNotificationUser/{id}', [NotificationController::class, 'readNotificationUser']);
    Route::put('/markAllAsReadUser', [NotificationController::class, 'markAllAsReadUser']);
    //reports
    Route::post('/userReports', [ReportsController::class, 'userReports']);

});
