<?php

use App\Http\Controllers\Login\LoginController;
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

Route::get('/', function () {
    return view('home.index');
})->name('login');

Route::middleware(['web'])->group(function () {
    Route::get('reload-captcha', [LoginController::class, 'recaptcha'])->name('recaptcha');
    Route::post('login-action', [LoginController::class, 'actionLogin'])->name('login.action');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('change-password', [LoginController::class, 'changePassword'])->name('change.password');
});
