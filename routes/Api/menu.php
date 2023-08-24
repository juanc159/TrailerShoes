<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Menu
|--------------------------------------------------------------------------
*/
Route::post('/menu-list', [MenuController::class, 'list'])->name('menu.list');
Route::post('/menu-create', [MenuController::class, 'store'])->name('menu.store');
Route::put('/menu-update', [MenuController::class, 'store'])->name('menu.update');
Route::delete('/menu-delete/{id}', [MenuController::class, 'delete'])->name('menu.delete');
Route::get('/menu-info/{id}', [MenuController::class, 'info'])->name('menu.info');
