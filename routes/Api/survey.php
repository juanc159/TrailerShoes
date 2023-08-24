<?php

use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Survey
|--------------------------------------------------------------------------
*/
Route::post('/survey-list', [SurveyController::class, 'list'])->name('survey.list');
Route::post('/survey-create', [SurveyController::class, 'store'])->name('survey.store');
Route::put('/survey-update', [SurveyController::class, 'store'])->name('survey.update');
Route::delete('/survey-delete/{id}', [SurveyController::class, 'delete'])->name('survey.delete');
Route::get('/survey-info/{id}', [SurveyController::class, 'info'])->name('survey.info');
Route::post('/survey-sendMail', [SurveyController::class, 'sendMail'])->name('survey.sendMail');
Route::get('/survey-infoReport/{id}', [SurveyController::class, 'infoReport'])->name('survey.infoReport');
