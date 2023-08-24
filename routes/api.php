<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DynamicMenuPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Ver encuesta a responde
Route::get('/survey-getInfoToAnswer/{id}/{email}', [SurveyController::class, 'getInfoToAnswer'])->name('survey.getInfoToAnswer');
Route::post('/survey-saveAnswer', [SurveyController::class, 'saveAnswer'])->name('survey.saveAnswer');

Route::get('/dynamicMenuPage-principal', [DynamicMenuPageController::class, 'principal'])->name('dynamicPage.principal');

Route::post('/dynamicMenuPage-preview', [DynamicMenuPageController::class, 'preview'])->name('dynamicPage.preview');
Route::get('/company-info/{id}', [CompanyController::class, 'info'])->name('company.info');
