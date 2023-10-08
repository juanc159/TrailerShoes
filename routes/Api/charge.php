<?php

use App\Http\Controllers\ChargeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Charge
|--------------------------------------------------------------------------
*/
Route::post('/charge-list', [ChargeController::class, 'list'])->name('charge.list');
Route::post('/charge-create', [ChargeController::class, 'store'])->name('charge.store');
Route::put('/charge-update', [ChargeController::class, 'store'])->name('charge.update');
Route::delete('/charge-delete/{id}', [ChargeController::class, 'delete'])->name('charge.delete');
Route::get('/charge-info/{id}', [ChargeController::class, 'info'])->name('charge.info');
Route::get('/charge-dataForm', [ChargeController::class, 'dataForm'])->name('charge.dataForm');
Route::post('/charge-changeState', [ChargeController::class, 'changeState'])->name('charge.changeState');
Route::post('/charge-select2InfiniteList', [ChargeController::class, 'select2InfiniteList'])->name('charge.select2InfiniteList');
