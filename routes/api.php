<?php

use App\Http\Controllers\MultiplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileOrderController;
use App\Http\Controllers\EntryController;
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
Route::middleware(['auth.token'])->group(function () {

    Route::group(['prefix' => 'app'], function () {
        Route::get('user', [UserController::class, 'index']);                                  # /api/app/user

        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', [CustomerController::class, 'index']);                             # /api/app/customers
            Route::post('/filter', [CustomerController::class, 'filter']);                     # /api/app/customers/filter
        });

        Route::get('/entries', [EntryController::class, 'index']);                             # /api/app/entries

        Route::group(['prefix' => 'multiplier'], function () {
            Route::get('/', [MultiplierController::class, 'index']);                           # /api/app/multiplier
            Route::post('/save/{id}', [MultiplierController::class, 'updateOrCreate']);        # /api/app/multiplier/save/id
            Route::get('/history', [MultiplierController::class, 'getHistory']);               # /api/app/multiplier/history
        });

        Route::group(['prefix' => 'profile-order'], function () {
            Route::get('/user/{id}', [CustomerProfileOrderController::class, 'getUserProfileOrders']);       # /api/app/profile-order/user/id
        });
    });
});