<?php

use App\Http\Controllers\ThriftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Loan
|--------------------------------------------------------------------------
*/
Route::post('/thrift-list', [ThriftController::class, 'list'])->name('thrift.list');
Route::post('/thrift-create', [ThriftController::class, 'store'])->name('thrift.store');
Route::put('/thrift-update', [ThriftController::class, 'store'])->name('thrift.update');
Route::delete('/thrift-delete/{id}', [ThriftController::class, 'delete'])->name('thrift.delete');
Route::get('/thrift-info/{id}', [ThriftController::class, 'info'])->name('thrift.info');
Route::post('/thrift-dataForm', [ThriftController::class, 'dataForm'])->name('thrift.dataForm');
Route::post('/thrift-changeState', [ThriftController::class, 'changeState'])->name('thrift.changeState');
