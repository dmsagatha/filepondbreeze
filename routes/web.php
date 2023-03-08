<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('avatar/{userId}', [UserController::class, 'getAvatar']);

// FilePond
Route::post('upload', [UserController::class, 'store']);

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  
  Route::prefix('admin')->group(function () {
    Route::resource('posts', PostController::class)->only([
      'index', 'store'
    ]);

    // Dropzone and Laravel Media Library
    // https://github.com/junicotandiago198/crud-laravel-dropzone
    // https://spatie.be/docs/laravel-medialibrary/v10/installation-setup
    // https://laraveldaily.teachable.com/courses/1324478/lectures/30707684
    /* Route::resource('products', ProductController::class);
    Route::post('/store/media', [ProductController::class, 'storeMedia'])->name('products.storeMedia');
 */


Route::prefix('products')->group(function () {
  Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
  Route::get('/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
  Route::post('/store', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
  Route::post('/store/media', [\App\Http\Controllers\ProductController::class, 'storeMedia'])->name('products.storeMedia');
  Route::get('/{id}', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
  Route::put('/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
  Route::delete('/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.delete');
});
  });
  // FilePond
  Route::post('/tmp_upload', [PostController::class, 'tmpUplaod']);
  Route::delete('/tmp_delete', [PostController::class, 'tmpDelete']);
});

require __DIR__.'/auth.php';