<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// U
use App\Http\Controllers\Services\UsersController;
use App\Http\Controllers\Services\UserRolesController;


// Common Routes
Route::post('/Users/setUser', [UsersController::class, 'setUser'])->name('users.setUser');





// Auth Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // U
    Route::prefix('Users')->group(function (){
        Route::post('/getUsers', [UsersController::class, 'getUsers'])->name('UserRoles.getUsers');
        Route::post('/getUser', [UsersController::class, 'getUser'])->name('users.getUser');
    });

    Route::prefix('UserRoles')->group(function (){
        Route::post('/getUserRoles', [UserRolesController::class, 'getUserRoles'])->name('userRoles.getUserRoles');
        Route::post('/getUserRole', [UserRolesController::class, 'getUserRole'])->name('userRoles.getUserRole');
        Route::post('/setUserRole', [UserRolesController::class, 'setUserRole'])->name('userRoles.setUserRole');
    });



});
