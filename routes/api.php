<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Services\UsersController;


Route::post('/Users/getUser', [UsersController::class, 'getUser'])->name('getUser');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
