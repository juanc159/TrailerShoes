<?php

use App\Http\Controllers\DynamicPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dynamic Pages
|--------------------------------------------------------------------------
*/

Route::post('/dynamicPage-list', [DynamicPageController::class, 'list'])->name('dynamicPage.list');
Route::post('/dynamicPage-create', [DynamicPageController::class, 'store'])->name('dynamicPage.store');
Route::put('/dynamicPage-update', [DynamicPageController::class, 'store'])->name('dynamicPage.update');
Route::delete('/dynamicPage-delete/{id}', [DynamicPageController::class, 'delete'])->name('dynamicPage.delete');
Route::get('/dynamicPage-info/{id}', [DynamicPageController::class, 'info'])->name('dynamicPage.info');

// Route::post('/dynamicPage-preview', [DynamicPageController::class, 'preview'])->name('dynamicPage.preview');
