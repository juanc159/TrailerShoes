<?php

use App\Http\Controllers\Usercontroller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/
Route::post('/user-list', [Usercontroller::class, 'list'])->name('user.list');
Route::post('/user-create', [Usercontroller::class, 'store'])->name('user.store');
Route::put('/user-update', [Usercontroller::class, 'store'])->name('user.update');
Route::delete('/user-delete/{id}', [Usercontroller::class, 'delete'])->name('user.delete');
Route::get('/user-info/{id}', [Usercontroller::class, 'info'])->name('user.info');
Route::post('/user-dataForm', [Usercontroller::class, 'dataForm'])->name('user.dataForm');
Route::post('/user-changeState', [Usercontroller::class, 'changeState'])->name('user.changeState');
Route::post('/user-select2', [Usercontroller::class, 'select2InfiniteList'])->name('user.select2');
