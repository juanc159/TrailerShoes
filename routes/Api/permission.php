<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Permission
|--------------------------------------------------------------------------
*/
Route::post('/permission-list', [PermissionController::class, 'list'])->name('permission.list');
Route::post('/permission-create', [PermissionController::class, 'store'])->name('permission.store');
Route::put('/permission-update', [PermissionController::class, 'store'])->name('permission.update');
Route::delete('/permission-delete/{id}', [PermissionController::class, 'delete'])->name('permission.delete');
Route::get('/permission-info/{id}', [PermissionController::class, 'info'])->name('permission.info');
Route::get('/permission-dataForm', [PermissionController::class, 'dataForm'])->name('permission.dataForm');
