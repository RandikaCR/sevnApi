<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd('Hello from the SEVN API');
    return view('welcome');
});
