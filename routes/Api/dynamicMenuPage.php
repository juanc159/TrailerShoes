<?php

use App\Http\Controllers\DynamicMenuPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dynamic Menu Pages
|--------------------------------------------------------------------------
*/
Route::post('/dynamicMenuPage-list', [DynamicMenuPageController::class, 'list'])->name('dynamicMenuPage.list');
Route::post('/dynamicMenuPage-create', [DynamicMenuPageController::class, 'store'])->name('dynamicMenuPage.store');
Route::put('/dynamicMenuPage-update', [DynamicMenuPageController::class, 'store'])->name('dynamicMenuPage.update');
Route::delete('/dynamicMenuPage-delete/{id}', [DynamicMenuPageController::class, 'delete'])->name('dynamicMenuPage.delete');
Route::get('/dynamicMenuPage-info/{id}', [DynamicMenuPageController::class, 'info'])->name('dynamicMenuPage.info');
Route::post('/dynamicMenuPage-changeState', [DynamicMenuPageController::class, 'changeState'])->name('dynamicMenuPage.changeState');

Route::get('/dynamicMenuPage-infoPage/{id}', [DynamicMenuPageController::class, 'infoPage'])->name('dynamicMenuPage.infoPage');
Route::post('/dynamicMenuPage-pageCreate', [DynamicMenuPageController::class, 'storePage'])->name('dynamicPage.storePage');
Route::post('/dynamicMenuPage-preview', [DynamicMenuPageController::class, 'preview'])->name('dynamicPage.preview');
