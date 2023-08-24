<?php

use App\Http\Controllers\SocialNetworkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Social Network
|--------------------------------------------------------------------------
*/
Route::post('/socialNetwork-list', [SocialNetworkController::class, 'list'])->name('socialNetwork.list');
Route::post('/socialNetwork-create', [SocialNetworkController::class, 'store'])->name('socialNetwork.store');
Route::put('/socialNetwork-update', [SocialNetworkController::class, 'store'])->name('socialNetwork.update');
Route::delete('/socialNetwork-delete/{id}', [SocialNetworkController::class, 'delete'])->name('socialNetwork.delete');
Route::get('/socialNetwork-info/{id}', [SocialNetworkController::class, 'info'])->name('socialNetwork.info');
