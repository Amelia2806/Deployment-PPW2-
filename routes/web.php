<?php

use Illuminate\Support\Facades\Route;
 
Route::get('/', function () {
    return view('welcome');
});
 
Route::get('/about', function () {
    return view('about',[
        "name" => "jay",
        "email" => "jay@gmail.com"
    ]);
});

Route::get('/', function () {
    return view('home');
});

Route::get('/post', [PostController::class, 'index']);