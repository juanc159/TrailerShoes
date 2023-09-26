<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Employed
|--------------------------------------------------------------------------
*/
Route::post('/employee-list', [EmployeeController::class, 'list'])->name('employee.list');
Route::post('/employee-create', [EmployeeController::class, 'store'])->name('employee.store');
Route::put('/employee-update', [EmployeeController::class, 'store'])->name('employee.update');
Route::delete('/employee-delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');
Route::get('/employee-info/{id}', [EmployeeController::class, 'info'])->name('employee.info');
Route::post('/employee-dataForm', [EmployeeController::class, 'dataForm'])->name('employee.dataForm');
Route::post('/employee-changeState', [EmployeeController::class, 'changeState'])->name('employee.changeState');
Route::post('/employee-select2InfiniteList', [EmployeeController::class, 'select2InfiniteList'])->name('employee.select2InfiniteList');
