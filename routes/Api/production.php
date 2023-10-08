<?php

use App\Http\Controllers\ProductionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Production
|--------------------------------------------------------------------------
*/
Route::post('/production-list', [ProductionController::class, 'list'])->name('production.list');
Route::post('/production-create', [ProductionController::class, 'store'])->name('production.store');
Route::put('/production-update', [ProductionController::class, 'store'])->name('production.update');
Route::delete('/production-delete/{id}', [ProductionController::class, 'delete'])->name('production.delete');
Route::get('/production-info/{id}', [ProductionController::class, 'info'])->name('production.info');
Route::get('/production-dataForm', [ProductionController::class, 'dataForm'])->name('production.dataForm');
Route::post('/production-changeState', [ProductionController::class, 'changeState'])->name('production.changeState');
