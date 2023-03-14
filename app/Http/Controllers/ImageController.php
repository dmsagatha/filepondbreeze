<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
  public function store(Request $request)
  {
    $image      = $request->file('file');
    $filename   = Str::uuid() . "." . $image->extension();

    $imageServer = Image::make($image);
    $imageServer->fit(100, 100);
    // $imagePath   = public_path('uploads') . '/' . $filename;
    $imagePath  = public_path('storage/uploads/') . '/' . $filename;
    $imageServer->save($imagePath);

    return response()->json(['image' => $filename]);
  }
}