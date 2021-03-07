<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WizardController;
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

// Example Routes
Route::view('/', 'landing')->name('landing');
Route::match(['get', 'post'], '/dashboard', function(){
    return view('dashboard');
})->name('dashboard');
Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank');
Route::any('/wizard/{page?}', [WizardController::class, 'index']);
Route::match(['get', 'post'], '/account/{element?}', [AccountController::class, 'index'])->name('account');
Route::match(['get', 'post'], '/login/{custom?}', [AccountController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/recover/{custom?}', [AccountController::class, 'recover'])->name('recover');
Route::match(['get', 'post'], '/register/{custom?}', [AccountController::class, 'register'])->name('register');


Route::match(['get', 'post'], '/test/{element?}', [TestController::class, 'index'])->name('test');
