<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/

Route::post('/event-list', [EventController::class, 'list'])->name('event.list');
Route::post('/event-create', [EventController::class, 'store'])->name('event.store');
Route::put('/event-update', [EventController::class, 'store'])->name('event.update');
Route::delete('/event-delete/{id}', [EventController::class, 'delete'])->name('event.delete');
Route::get('/event-info/{id}', [EventController::class, 'info'])->name('event.info');
Route::post('/event-dataForm', [EventController::class, 'dataForm'])->name('event.dataForm');
Route::post('/event-changeState', [EventController::class, 'changeState'])->name('event.changeState');
Route::post('/event-guestsInformation', [EventController::class, 'guestsInformation'])->name('event.guestsInformation');
Route::post('/event-guest-answerInvitation', [EventController::class, 'guestanswerInvitation'])->name('event.guestsInformation');
