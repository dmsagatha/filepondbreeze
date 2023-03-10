<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index(): View
  {
    return view('admon.categories.index', [
      'categories' => Category::latest()->get()
    ]);
  }
  
  public function create(): Response
  {
    return response()->view('admon.categories.form');
  }
  
  public function store(CategoryRequest $request): RedirectResponse
  {
    $validated = $request->validated();

    if ($request->hasFile('featured_image')) {
      // Poner la imagen en el almacenamiento público
      $file = Storage::disk('public')->put('categories', request()->file('featured_image'), 'public');
      // Obtener la ruta de la imagen en la url
      $path = Storage::url($file);
      $validated['featured_image'] = $path;
    }

    // Insertar solo solicitudes que ya hayan sido validadas en StoreRequest
    $create = Category::create($validated);

    if($create) {
      return to_route('categories.index')->with('success', 'Categoría creada');
    }

    return abort(500);
  }
  
  public function show(Category $category)
  {
  }
  
  public function edit(Category $category): Response
  {
    return response()->view('admon.categories.form', [
      'category' => $category
    ]);
  }
  
  public function update(CategoryRequest $request, Category $category): RedirectResponse
  {
    $validated = $request->validated();

    if ($request->hasFile('featured_image')) {
      // Obtenga la ruta de la imagen actual y reemplace la ruta de almacenamiento con la ruta pública
      $currentImage = str_replace('/storage', '/public', $category->featured_image);
      // Eliminar imagen actual
      Storage::delete($currentImage);

      $file = Storage::disk('public')->put('categories', request()->file('featured_image'), 'public');
      $path = Storage::url($file);
      $validated['featured_image'] = $path;
    }

    $update = $category->update($validated);

    if($update) {
      return to_route('categories.index')->with('success', 'Categoría actualizada');
    }

    return abort(500);
  }
  
  public function destroy(Category $category): RedirectResponse
  {
    $currentImage = str_replace('/storage', '/public', $category->featured_image);
    Storage::delete($currentImage);
    
    $delete = $category->delete();

    if($delete) {
      return to_route('categories.index')->with('success', 'Categoría eliminada');
    }

    return abort(500);
  }
}