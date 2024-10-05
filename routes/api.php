<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',[UserController::class,'store']);
Route::post('/login',[UserController::class,'login'])->name('login');
Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');

Route::group(['middleware'=>'auth:sanctum'],function(){

    Route::resource('posts',PostController::class);
    Route::get('posts/{post}/comments',[PostController::class,'comments'])->name('posts.comments');
    Route::resource('categories',CategoryController::class);
    Route::get('categories/{category}/posts',[CategoryController::class,'posts']);
    Route::get('categories/{category}/comments',[CategoryController::class,'comments']);

    Route::post('comments',[CommentController::class,'store']);
    Route::delete('comments/{comment}',[CommentController::class,'delete']);
});


