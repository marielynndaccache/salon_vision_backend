<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/Unauthorized', function () {
    return "Unauthorized";
})->name("Unauthorized");