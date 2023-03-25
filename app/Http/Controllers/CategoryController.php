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

  public function store(CategoryRequest $request): RedirectResponse
  {
    Category::create($request->all());

    Session()->flash('statusCode', 'success');

    return redirect(route('categories.index'))->withStatus('Registro creado');
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

  public function removefile(Request $request)
  {
    $image     = $request['removeimageName'];
    $imagepath = storage_path('app/public/categories/');
    unlink($imagepath . $request['removeimageName']);

    return $image;
  }

  public function show(Category $category)
  {
  }

  public function edit(Category $category): Response
  {
    if (!is_null($category)) {
      // return response()->view('admon.categories.edit', [
      return response()->view('admon.categories.form', [
        'category' => $category,
        'featured_image' => explode(',', $category->featured_image)
      ]);
    }

    return redirect(route('categories.index'));
  }

  // FUNCIONAL CON VALIDADOR INTERNO
  /* public function update(Request $request, Category $category): RedirectResponse
  {
    if (!is_null($category)) {
      $request->validate([
        'name' => 'required|unique:categories,name,' . $category->id
      ]);

      $category->name = $request['name'];
      $category->featured_image = $request['featured_image'] ? $request['featured_image'] : $category->featured_image;
      $category->save();

      return redirect()->route('categories.index')->withStatus('Registro actualizado');
    } else {
      return redirect()->route('categories.index');
    }
  } */

  public function update(CategoryRequest $request, Category $category): RedirectResponse
  {
    $imagen_path = public_path('storage/categories/' . $category->featured_image);

    if (File::exists($imagen_path)) {
      unlink($imagen_path);
    }

    $category->update($request->all());

    Session()->flash('statusCode', 'info');

    return redirect()->route('categories.index')->withStatus('Registro actualizado');
  }

  public function destroy(Category $category): RedirectResponse
  {
    $category->delete();
    
    // $imagen_path = storage_path('app/public/categories/'.$category->featured_image);
    $imagen_path = public_path('storage/categories/' . $category->featured_image);

    if (File::exists($imagen_path)) {
      unlink($imagen_path);
    }

    Session()->flash('statusCode', 'warning');

    return to_route('categories.index')->withStatus('Registro eliminado permanentemente!.');
  }
}
