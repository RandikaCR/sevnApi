<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;

Route::get('/', function () {
    return view('front');
});

Route::get('/test-mail', [testController::class, 'testMail'])->name('test.testMail');
