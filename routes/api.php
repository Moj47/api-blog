<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/user',[UserController::class,'store']);

Route::resource('posts',PostController::class);

Route::resource('categories',CategoryController::class);
