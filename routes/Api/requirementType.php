<?php

use App\Http\Controllers\RequirementTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Request Type
|--------------------------------------------------------------------------
*/
Route::post('/requirementType-list', [RequirementTypeController::class, 'list'])->name('requirementType.list');
Route::post('/requirementType-create', [RequirementTypeController::class, 'store'])->name('requirementType.store');
Route::put('/requirementType-update', [RequirementTypeController::class, 'store'])->name('requirementType.update');
Route::delete('/requirementType-delete/{id}', [RequirementTypeController::class, 'delete'])->name('requirementType.delete');
Route::get('/requirementType-info/{id}', [RequirementTypeController::class, 'info'])->name('requirementType.info');
Route::post('/requirementType-dataForm', [RequirementTypeController::class, 'dataForm'])->name('requirementType.dataForm');
Route::post('/requirementType-changeState', [RequirementTypeController::class, 'changeState'])->name('requirementType.changeState');
Route::post('/requirementType-select2', [RequirementTypeController::class, 'select2InfiniteList'])->name('requirementType.select2');
