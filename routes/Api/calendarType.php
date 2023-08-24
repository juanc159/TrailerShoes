<?php

use App\Http\Controllers\CalendarTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Calendar Type
|--------------------------------------------------------------------------
*/
Route::post('/calendarType-list', [CalendarTypeController::class, 'list'])->name('calendarType.list');
Route::post('/calendarType-create', [CalendarTypeController::class, 'store'])->name('calendarType.store');
Route::put('/calendarType-update', [CalendarTypeController::class, 'store'])->name('calendarType.update');
Route::delete('/calendarType-delete/{id}', [CalendarTypeController::class, 'delete'])->name('calendarType.delete');
Route::get('/calendarType-info/{id}', [CalendarTypeController::class, 'info'])->name('calendarType.info');
Route::post('/calendarType-dataForm', [CalendarTypeController::class, 'dataForm'])->name('calendarType.dataForm');
Route::post('/calendarType-changeState', [CalendarTypeController::class, 'changeState'])->name('calendarType.changeState');
