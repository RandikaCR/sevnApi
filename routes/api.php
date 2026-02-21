<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Services\UsersController;
use App\Http\Controllers\Services\UserRolesController;


Route::post('/Users/getUser', [UsersController::class, 'getUser'])->name('users.getUser');
Route::post('/Users/setUser', [UsersController::class, 'setUser'])->name('users.setUser');

// Auth Routes
Route::middleware('auth:sanctum')->group(function () {

    // U
    Route::prefix('UserRoles')->group(function (){
        Route::post('/getUserRoles', [UserRolesController::class, 'getUserRoles'])->name('UserRoles.getUserRoles');
        Route::post('/getUserRole', [UserRolesController::class, 'getUserRole'])->name('UserRoles.getUserRole');
        Route::post('/setUserRole', [UserRolesController::class, 'setUserRole'])->name('UserRoles.setUserRole');
    });




});
