<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index(): Response
  {
    return response()->view('products.index', [
      'products' => Product::latest()->get(),
      // 'mediaCollection' => $this->mediaCollection
    ]);
  }
  
  public function create(): Response
  {
    // return response()->view('products.create');
    return response()->view('products.form');
  }

  /* https://cdn.fs.teachablecdn.com/89p5visTTwO2N0v4O6OS
  https://cdn.fs.teachablecdn.com/LU4kLmI0QhWeVIJIFGeT --> 4' */
  public function store(Request $request): RedirectResponse
  {
    $product = Product::create([
      'name' => $request->name,
      'description' => $request->description,
    ]);

    // Si trae un archivo adicionarlo a la colección products
    /* if (isset($request->file)) {
      $product->addMediaFromRequest($request->file)->toMediaCollection('products');
    } */

    foreach ($request->input('photo', []) as $file) {
      // $product->addMedia(storage_path('app/public/products/' . $file))->toMediaCollection($this->mediaCollection);
      $product->addMedia(storage_path('app/public/products/' . $file))->toMediaCollection('products');
    }

    return redirect(route('products.index'));
  }

  public function storeMedia(Request $request)
  {
    $path = storage_path('app/public/products');

    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }

    $file = $request->file('file');
    $name = uniqid() . '_' . trim($file->getClientOriginalName());
    $file->move($path, $name);

    return response()->json([
      'name'          => $name,
      'original_name' => $file->getClientOriginalName()
    ]);
  }
  
  public function show(Product $product): Response
  {
  }
  
  public function edit(Product $product): Response
  {
    // $product = Product::find($id);

    // return view('products.edit', ['product' => $product, 'photos' => $product->getMedia($this->mediaCollection)]);
    return response()->view('products.form', [
      'product' => $product, 
      'photos'  => $product->getMedia('products')
    ]);
  }
  
  // public function update(Request $request, Product $product): RedirectResponse
  public function update(Request $request, $id)
  {
    $product = Product::with('photos')->find($id);
    $product->update([
      'name' => $request->name,
      'description' => $request->description,
    ]);

    if (count($product->photos) > 0) {
      foreach ($product->photos as $media) {
        if (!in_array($media->file_name, $request->input('photo', []))) {
            $media->delete();
        }
      }
    }

    $media = $product->photos->pluck('file_name')->toArray();

    foreach ($request->input('photo', []) as $file) {
      if (count($media) === 0 || !in_array($file, $media)) {
        $product->addMedia(storage_path('app/public/products/' . $file))->toMediaCollection('products');
      }
    }

    return redirect()->route('products.index');
  }
  
  public function destroy(Product $product): RedirectResponse
  {
  }
}