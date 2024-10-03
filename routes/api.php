<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/user',[UserController::class,'store']);

Route::resource('posts',PostController::class);
Route::get('posts/{post}/comments',[PostController::class,'comments']);
Route::resource('categories',CategoryController::class);
Route::get('categories/{category}/posts',[CategoryController::class,'posts']);

Route::post('comments',[CommentController::class,'store']);
Route::delete('comments/{comment}',[CommentController::class,'delete']);
