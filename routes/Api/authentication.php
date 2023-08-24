<?php

use App\Http\Controllers\PassportAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/dataForm', [PassportAuthController::class, 'dataForm'])->name('dataForm');
Route::post('/register', [PassportAuthController::class, 'register'])->name('register');
Route::post('/login', [PassportAuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('/get-user', [PassportAuthController::class, 'userInfo'])->name('userInfo');
});
