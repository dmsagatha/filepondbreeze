<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
  // TERCERA OPCION - https://laravel.sillo.org/un-site-dannonces-creer-une-annonce/
  private $images_path;
  
  public function __construct()
  {
    $this->images_path = public_path('storage/uploads');
  }

  public function store(Request $request)
  {
    // PRIMERA OPCION
    /* $image      = $request->file('file');
    $filename   = Str::uuid() . "." . $image->extension();

    $imageServer = Image::make($image);
    $imageServer->fit(100, 100);
    $imagePath  = public_path('storage/uploads/') . '/' . $filename;
    $imageServer->save($imagePath);

    return response()->json(['image' => $filename]); */

    // SEGUNDA OPCION
    /* $image    = $request->file('file');

    $filename = Str::uuid() . "." . $image->extension();
    $filepath = storage_path() . '/app/public/uploads/' . $filename;
    
    Image::make($image)->resize(100, null, function ($constraint) {
      $constraint->aspectRatio();
    })->save($filepath);

    return response()->json(['image' => $filename]); */

    // TERCERA OPCION - https://laravel.sillo.org/un-site-dannonces-creer-une-annonce/
    $image = $request->file('file');
    
    if (!is_dir($this->images_path)) {
      mkdir($this->images_path);
    }
    
    $filename = Str::uuid() . "." . $image->getClientOriginalExtension();
    
    Image::make($image)->resize(100, null, function ($constraint) {
      $constraint->aspectRatio();
    })->save($this->images_path . '/' . $filename);

    return response()->json(['image' => $filename]);
  }
}