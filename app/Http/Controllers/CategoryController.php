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
      $file = Storage::disk('public')->put('images/categories/featured-images', request()->file('featured_image'), 'public');
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
  
  public function show(Category $category): Response
  {
  }
  
  public function edit(Category $category): Response
  {
  }
  
  public function update(Request $request, Category $category): RedirectResponse
  {
  }
  
  public function destroy(Category $category): RedirectResponse
  {
  }
}