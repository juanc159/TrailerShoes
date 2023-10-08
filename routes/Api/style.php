<?php

use App\Http\Controllers\StyleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Style
|--------------------------------------------------------------------------
*/
Route::post('/style-list', [StyleController::class, 'list'])->name('style.list');
Route::post('/style-create', [StyleController::class, 'store'])->name('style.store');
Route::put('/style-update', [StyleController::class, 'store'])->name('style.update');
Route::delete('/style-delete/{id}', [StyleController::class, 'delete'])->name('style.delete');
Route::get('/style-info/{id}', [StyleController::class, 'info'])->name('style.info');
Route::get('/style-dataForm', [StyleController::class, 'dataForm'])->name('style.dataForm');
Route::post('/style-changeState', [StyleController::class, 'changeState'])->name('style.changeState');
Route::post('/style-select2InfiniteList', [StyleController::class, 'select2InfiniteList'])->name('style.select2InfiniteList');
