<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;

Route::get('/', function () {
    return view('front');
});

Route::get('/test-mail', [testController::class, 'testMail'])->name('test.testMail');

Route::get('/email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('/email/resend', 'Auth\VerificationController@resend')->name('verification.send'); // Note: 'verification.send' in newer Laravel
