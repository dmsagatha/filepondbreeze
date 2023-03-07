<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index()
  {
    return response()->view('products.index', [
      'products' => Product::latest()->get(),
      // 'mediaCollection' => $this->mediaCollection
    ]);
  }
  
  public function create()
  {
  }

  /* https://cdn.fs.teachablecdn.com/89p5visTTwO2N0v4O6OS
  https://cdn.fs.teachablecdn.com/LU4kLmI0QhWeVIJIFGeT --> 4' */
  public function store(Request $request)
  {
    $product = Product::create([
      'name' => $request->name,
      'description' => $request->description,
    ]);

    foreach ($request->input('photo', []) as $file) {
      // $product->addMedia(storage_path('app/public/products/' . $file))->toMediaCollection($this->mediaCollection);
      $product->addMedia(storage_path('app/public/products/' . $file))->toMediaCollection('products');
    }

    return redirect()->route('products.index');
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
      'original_name' => $file->getClientOriginalName(),
    ]);
  }
  
  public function show(Product $product): Response
  {
  }
  
  public function edit(Product $product): Response
  {
  }
  
  public function update(Request $request, Product $product): RedirectResponse
  {
  }
  
  public function destroy(Product $product): RedirectResponse
  {
  }
}