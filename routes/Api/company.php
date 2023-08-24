<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Company
|--------------------------------------------------------------------------
*/
Route::post('/company-list', [CompanyController::class, 'list'])->name('company.list');
Route::post('/company-create', [CompanyController::class, 'store'])->name('company.store');
Route::get('/company-info/{id}', [CompanyController::class, 'info'])->name('company.info');

// Route::put('/charge-update', [CompanyController::class, 'store'])->name('charge.update');
// Route::delete('/charge-delete/{id}', [CompanyController::class, 'delete'])->name('charge.delete');
// Route::post('/charge-dataForm', [CompanyController::class, 'dataForm'])->name('charge.dataForm');
// Route::post('/charge-changeState', [CompanyController::class, 'changeState'])->name('charge.changeState');
// Route::post('/charge-select2InfiniteList', [CompanyController::class, 'select2InfiniteList'])->name('charge.select2InfiniteList');
