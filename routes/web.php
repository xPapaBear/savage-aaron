<?php

use App\Mail\EmailEntry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/login', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth.shopify', 'itp'])->group(function () {
    Route::get('/', function () {
		return view('spa');
	});
	
	Route::get('/{path}', [App\Http\Controllers\SpaController::class, 'index'])->where('path', '(.*)');
	Route::get('/send', function(){
		Mail::mailer('smtp')->to('dummyemail@gmail.com')->send(new EmailEntry());
		logger('testt');
	});
});