<?php

use App\Http\Controllers\LogInfoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Audit
|--------------------------------------------------------------------------
*/
Route::post('/audit-list', [LogInfoController::class, 'list'])->name('audit.list');
Route::post('/audit-create', [LogInfoController::class, 'store'])->name('audit.store');
