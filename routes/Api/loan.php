<?php

use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Loan
|--------------------------------------------------------------------------
*/
Route::post('/loan-list', [LoanController::class, 'list'])->name('loan.list');
Route::post('/loan-create', [LoanController::class, 'store'])->name('loan.store');
Route::put('/loan-update', [LoanController::class, 'store'])->name('loan.update');
Route::delete('/loan-delete/{id}', [LoanController::class, 'delete'])->name('loan.delete');
Route::get('/loan-info/{id}', [LoanController::class, 'info'])->name('loan.info');
Route::post('/loan-dataForm', [LoanController::class, 'dataForm'])->name('loan.dataForm');
Route::post('/loan-changeState', [LoanController::class, 'changeState'])->name('loan.changeState');
