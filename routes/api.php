<?php

use App\Http\Controllers\MultiplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\TestController;
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

/**
 * Routes for auth.token
 */
Route::group(['middleware' => 'auth.token', 'prefix' => 'app'], function () {

    // User Groups
    Route::group(['prefix' => 'user'], function () {
        # /api/app/user
        Route::get('/', [UserController::class, 'index']);
    });
    
    // Dashboard Groups
    Route::group(['prefix' => 'dashboard'], function () {
        # /api/app/dashboard
        Route::get('/', [DashboardController::class, 'index']);
        # /api/app/dashboard/multiplier-history/five
        Route::get('multiplier-history/five', [DashboardController::class, 'multiplierHistoryFive']);
        # /api/app/dashboard/top-give-customer/entry-points
        Route::get('top-five-customer/entry-points', [DashboardController::class, 'topFiveCustomerEP']);
    });

    // Customers Groups
    Route::group(['prefix' => 'customers'], function () {
        # /api/app/customers
        Route::get('/', [CustomerController::class, 'index']);
        # /api/app/customers/filter 
        Route::post('filter', [CustomerController::class, 'filter']);
    });

    // Entries Groups
    Route::group(['prefix' => 'entries'], function () {
        # /api/app/entries
        Route::get('/', [EntryController::class, 'index']);
    });

    // Multiplier Groups
    Route::group(['prefix' => 'multiplier'], function () {
        # /api/app/multiplier
        Route::get('/', [MultiplierController::class, 'index']);
        # /api/app/multiplier/save/id
        Route::post('save/{id}', [MultiplierController::class, 'updateOrCreate']);
        # /api/app/multiplier/history
        Route::get('history', [MultiplierController::class, 'getHistory']);
    });
    
    // Profile Order Groups
    Route::group(['prefix' => 'profile-order'], function () {
        # /api/app/profile-order/user/id
        Route::get('user/{id}', [CustomerProfileOrderController::class, 'getUserProfileOrders']);
    });

});

/**
 * Routes for TESTING on POSTMAN
 */
Route::group(['prefix' => 'test'], function () {
    /**
     * Dashboard Top 5 Customer Entry Points
     */
    Route::get('email', [TestController::class, 'testTopCustomerEP']);
});