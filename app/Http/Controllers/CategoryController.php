<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\File;
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
    // return response()->view('admon.categories.create');
    return response()->view('admon.categories.form');
  }
  
  /* public function store(CategoryRequest $request): RedirectResponse
  {
    $validated = $request->validated();

    if ($request->hasFile('featured_image')) {
      // Poner la imagen en el almacenamiento público
      $file = Storage::disk('public')->put('categories', request()->file('featured_image'), 'public');
      // Obtener la ruta de la imagen en la url
      $path = Storage::url($file);
      $validated['featured_image'] = $path;
    }

    // Insertar solo solicitudes que ya hayan sido validadas en CategoryRequest
    $create = Category::create($validated);

    if($create) {
      return to_route('categories.index')->with('success', 'Categoría creada');
    }

    return abort(500);
  } */
  
  public function store(CategoryRequest $request): RedirectResponse
  {
    /* $request->validate([
        'name' => 'required|min:3|unique:categories'
    ]);

    $categories = new Category();
    $categories->name = $request['name'];
    $categories->featured_image = $request['featured_image'];
    $categories->save(); */

    /* Category::create([
      'name'           => $request['name'],
      'featured_image' =>  $request['featured_image']
    ]); */

    Category::create($request->all());

    return redirect(route('categories.index'))->with('success', 'Categoría creada');
  }

  public function dropzonestore(Request $request)
  {
    $image = $request->file('featured_image');

    foreach ($image as $images) {
      $imagename = uniqid() . "." . $images->getClientOriginalExtension();
      // $images->move(storage_path('app/public/categories'), $imagename);
      $images->storeAs('categories', $imagename);
    }
    return $imagename;
  }
    
  public function dropzone_view()
  {
    return view("dropzone");
  }

  public function removefile(Request $request)
  {
    $image = $request['removeimageName'];
    $imagepath = storage_path('app/public/categories/');
    unlink($imagepath.$request['removeimageName']);
    
    return $image;
  }
  
  public function show(Category $category)
  {
  }
  
  // public function edit($id): Response
  public function edit(Category $category): Response
  {
    // $category = Category::find($id);
    // dd($category);

    if (!is_null($category)) {
      // return response()->view('admon.categories.edit', [
      return response()->view('admon.categories.form', [
        'category' => $category,
        'featured_image' => explode(',', $category->featured_image)
      ]);
    }
  }
  
  // public function update(Request $request, $id): RedirectResponse
  public function update(CategoryRequest $request, Category $category): RedirectResponse
  {
    /* $category = Category::find($id);

    if (!is_null($category)) {
      $request->validate([
        'name' => 'required|min:3|unique:categories,name,'.$category->id
      ]);

      $category->name = $request['name'];
      $category->save();

      return to_route('categories.index')->with('success', 'Categoría actualizada');
    } else {
      return abort(500);
    } */

    $category->update($request->all());

    return to_route('categories.index')->with('success', 'Categoría actualizada');
  }
  
  public function destroy(Category $category): RedirectResponse
  {
    $category->delete();
    $imagen_path = public_path('storage/categories/'.$category->featured_image);
    // $imagen_path = storage_path('app/public/categories/'.$category->featured_image);

    if (File::exists($imagen_path)) {
      unlink($imagen_path);
    }

    return redirect(route('categories.index'))->with('success', 'Categoría eliminada');
  }
}