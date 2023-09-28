<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{user}/{profile}', [UserController::class, 'getUserImage']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    Route::get('/export', [UserController::class, 'export']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::get('/export', [PostController::class, 'export']);
    Route::post('/import',[PostController::class,'import']);

    Route::post('/users/{user}/changePassword', [UserController::class, 'changePassword']);

    Route::post('/logout', [LoginController::class, 'logout']);
});

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgotPassword', [PasswordController::class, 'forgotPassword']);
Route::post('/resetPassword', [PasswordController::class, 'resetPassword']);
