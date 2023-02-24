<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Http\Controllers\Controller;

class PostController extends Controller
{
  public function index()
  {
    $posts = Post::all();

    return view('posts.index', compact('posts'));
  }
  
  public function store(Request $request)
  {
    $request->validate([
      'title'     => ['required', 'unique:' . Post::class],

      // Se quita para FilePond
      // 'photo'   => 'image'
    ]);

    $post = Post::create([
      'title' => $request->title,
      'photo' => !empty($filename) ? $filename : 'default_photo.png'
    ]);

    // FilePond
    $temporaryFile = TemporaryFile::where('folder', $request->photo)->first();

    if ($temporaryFile) {
      $post->addMedia(storage_path('app/public/posts/tmp/' . $request->photo . '/' . $temporaryFile->filename))
          ->toMediaCollection('posts');
      
      // Eliminar directorio y archivo temporal
      File::deleteDirectory(storage_path('app/public/posts/tmp/' . $request->photo));

      // Eliminar el archivo temporal del modelo asociado
      $temporaryFile->delete();
    }
  }
  
  // FilePond
  public function tmpUplaod(Request $request)
  {
    if ($request->hasFile('photo')) {
      $file = $request->file('photo');
      $filename = $file->getclientOriginalName();
      $folder = uniqid() . '-' . now()->timestamp;
      $file->storeAs('posts/tmp/' . $folder, $filename);

      TemporaryFile::create([
        'folder' => $folder,
        'filename' => $filename
      ]);

      return $folder;
    }

    return '';
  }
}