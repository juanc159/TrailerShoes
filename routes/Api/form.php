<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
Route::post('/form-list', [FormController::class, 'list'])->name('form.list');
Route::post('/form-create', [FormController::class, 'store'])->name('form.store');
Route::put('/form-update', [FormController::class, 'store'])->name('form.update');
Route::delete('/form-delete/{id}', [FormController::class, 'delete'])->name('form.delete');
Route::get('/form-info/{id}', [FormController::class, 'info'])->name('form.info');
Route::post('/form-saveAnswer', [FormController::class, 'saveAnswer'])->name('survey.saveAnswer');
