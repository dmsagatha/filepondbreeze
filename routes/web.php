<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('avatar/{userId}', [UserController::class, 'getAvatar']);

// FilePond
Route::post('upload', [UserController::class, 'store']);

// Post
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::post('post/store', [PostController::class, 'store'])->name('posts.store');

// FilePond
Route::post('/tmp_upload', [PostController::class, 'tmpUplaod']);
Route::delete('/tmp_delete', [PostController::class, 'tmpDelete']);

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';