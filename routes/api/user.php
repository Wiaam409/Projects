
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\drugsController;
// use App\Http\Controllers\pharmacyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
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
Route::post('user/register',[AuthController::class, 'userRegister']);
Route::post('user/login',[AuthController::class, 'userLogin']);
Route::group( ['prefix' => 'user','middleware' => ['auth:user-api','scopes:user'] ],function(){
    Route::post('logout',[AuthController::class, 'userLogout']);
    Route::get('/showCategories/{id}', [DrugsController::class, 'showCategories']);
    Route::post('/search', [DrugsController::class, 'search']);
    Route::get('/showDetails/{id}', [DrugsController::class, 'showDetails']);
    Route::post('/makeOrder', [OrderController::class, 'makeOrder']);
});
