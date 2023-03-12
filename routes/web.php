<?php

use App\Http\Controllers\CategoryController;
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

    // PRODUCTS
    // Dropzone and Laravel Media Library
    // https://github.com/junicotandiago198/crud-laravel-dropzone
    // https://spatie.be/docs/laravel-medialibrary/v10/installation-setup
    // https://laraveldaily.teachable.com/courses/1324478/lectures/30707684
    Route::resource('products', ProductController::class);
    Route::post('/store/media', [ProductController::class, 'storeMedia'])->name('products.storeMedia');

    // CATEGORIES
    Route::resource('categories', CategoryController::class);
    // Resource
    /* Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/crear', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/editar', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); */
    // Dropzone
    Route::post('/dropzonestore', [CategoryController::class, 'dropzonestore'])->name('dropzone.store');
    Route::post('/removefile', [CategoryController::class,'removefile'])->name('remove.file');
    Route::get('/get-category-image/{id}',[CategoryController::class, 'getImages'])->name('getCategoryImage');
  });
  
  // FilePond
  Route::post('/tmp_upload', [PostController::class, 'tmpUplaod']);
  Route::delete('/tmp_delete', [PostController::class, 'tmpDelete']);
});

require __DIR__.'/auth.php';