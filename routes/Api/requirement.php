<?php

use App\Http\Controllers\RequirementController;
use App\Http\Controllers\RequirementManageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Requeriments
|--------------------------------------------------------------------------
*/
Route::post('/requirement-list', [RequirementController::class, 'list'])->name('requirement.list');
Route::post('/requirement-create', [RequirementController::class, 'store'])->name('requirement.create');
Route::put('/requirement-update', [RequirementController::class, 'store'])->name('requirement.update');
Route::delete('/requirement-delete/{id}', [RequirementController::class, 'delete'])->name('requirement.delete');
Route::get('/requirement-info/{id}', [RequirementController::class, 'info'])->name('requirement.info');
Route::get('/requirement-dataForm', [RequirementController::class, 'dataForm'])->name('requirement.dataForm');
Route::post('/requirement-changeState', [RequirementController::class, 'changeState'])->name('requirement.changeState');

/*
|--------------------------------------------------------------------------
| Requeriments Manage
|--------------------------------------------------------------------------
*/
Route::post('/requirement-manageCreate', [RequirementManageController::class, 'manageStore'])->name('requirement.manageStore');
Route::post('/requirement-manageDataForm', [RequirementManageController::class, 'manageDataForm'])->name('requirement.manageDataForm');
