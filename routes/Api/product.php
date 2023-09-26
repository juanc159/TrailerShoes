<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Product
|--------------------------------------------------------------------------
*/
Route::post('/product-list', [ProductController::class, 'list'])->name('product.list');
Route::post('/product-create', [ProductController::class, 'store'])->name('product.store');
Route::put('/product-update', [ProductController::class, 'store'])->name('product.update');
Route::delete('/product-delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
Route::get('/product-info/{id}', [ProductController::class, 'info'])->name('product.info');
Route::post('/product-dataForm', [ProductController::class, 'dataForm'])->name('product.dataForm');
Route::post('/product-changeState', [ProductController::class, 'changeState'])->name('product.changeState');
Route::post('/product-select2InfiniteList', [ProductController::class, 'select2InfiniteList'])->name('product.select2InfiniteList');
