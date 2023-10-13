<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PostController;
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

    ////routes for users CRUD
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/{profile}', [UserController::class, 'getUserImage']);
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/changePassword', [UserController::class, 'changePassword'])->name('users.changePassword');

    //routes for posts CRUD
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    //import posts
    Route::post('/import',[PostController::class,'import'])->name('import');

    Route::post('/logout', [LoginController::class, 'logout']);

});

//register user when not logged in
Route::post('/signup', [UserController::class, 'signup'])->name('signup');

//get all Users when not logged in
Route::get('/showAllUsers', [UserController::class, 'showAllUsers'])->name('showAllUsers');

//login user
Route::post('/login', [LoginController::class, 'login']);

//password reset
Route::post('/forgotPassword', [PasswordController::class, 'forgotPassword']);
Route::post('/resetPassword', [PasswordController::class, 'resetPassword']);

//show only posts with active status
Route::get('/showActivePosts', [PostController::class, 'showActivePosts']);

//export posts
Route::get('/export', [PostController::class, 'export'])->name('export');
