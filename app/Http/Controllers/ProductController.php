<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  private $mediaCollection = 'photo';

  public function index(): Response
  {
    return response()->view('products.index', [
      'products'        => Product::latest()->get(),
      'mediaCollection' => $this->mediaCollection,
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
    $this->validate($request, [
      'name' => 'required|unique:products',
      'description'  => 'required'
    ]);

    $product = Product::create([
      'name'        => $request->name,
      'description' => $request->description,
    ]);

    foreach ($request->input('photo', []) as $file)
    {
      $product->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection($this->mediaCollection);
    }

    return redirect(route('products.index'));
  }

  public function storeMedia(Request $request)
  {
    $path = storage_path('tmp/uploads');

    if (!file_exists($path))
    {
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
    return response()->view('products.form', [
      'product' => $product,
      'photos'  => $product->getMedia($this->mediaCollection),
    ]);
  }

  public function update(Product $product, Request $request): RedirectResponse
  {
    $product->update([
      'name'        => $request->name,
      'description' => $request->description,
    ]);

    if (count($product->photos) > 0)
    {
      foreach ($product->photos as $media)
      {
        if (!in_array($media->file_name, $request->input('photo', [])))
        {
          $media->delete();
        }
      }
    }

    $media = $product->photos->pluck('file_name')->toArray();

    foreach ($request->input('photo', []) as $file)
    {
      if (count($media) === 0 || !in_array($file, $media))
      {
        $product->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection($this->mediaCollection);
      }
    }

    return redirect()->route('products.index');
  }

  public function destroy(Product $product)
  {
    $product->delete();

    return redirect()->route('products.index');
  }
}