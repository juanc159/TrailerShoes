<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
*/
Route::post('/role-list', [RoleController::class, 'list'])->name('role.list');
Route::post('/role-create', [RoleController::class, 'store'])->name('role.store');
Route::put('/role-update', [RoleController::class, 'store'])->name('role.update');
Route::delete('/role-delete/{id}', [RoleController::class, 'delete'])->name('role.delete');
Route::get('/role-info/{id}', [RoleController::class, 'info'])->name('role.info');
Route::get('/role-dataForm', [RoleController::class, 'dataForm'])->name('role.dataForm');
