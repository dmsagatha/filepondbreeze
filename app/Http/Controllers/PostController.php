<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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
      'title' => ['required', 'unique:' . Post::class]
    ]);

    // FilePond
    $temporaryFile = TemporaryFile::where('folder', $request->photo)->first();

    if ($temporaryFile) {
      // Storage::copy('posts/tmp/' . $temporaryFile->folder . '/' . $temporaryFile->filename, 'posts/' . $temporaryFile->folder . '/' . $temporaryFile->filename);
      Storage::copy('posts/tmp/' . $temporaryFile->folder . '/' . $temporaryFile->filename, 'posts/' . '/' . $temporaryFile->filename);

      Post::create([
        'title' => $request->title,
        // 'photo' => $temporaryFile->folder . '/' . $temporaryFile->filename
        'photo' => $temporaryFile->filename
      ]);
      
      // Eliminar directorio y archivo temporal
      File::deleteDirectory(storage_path('app/public/posts/tmp/' . $request->photo));
      // Storage::deleteDirectory('posts/tmp/' . $temporaryFile->folder);

      // Eliminar el archivo temporal del modelo asociado
      $temporaryFile->delete();

      return to_route('posts.index')->with('success', 'Post creado');
    }
  }
  
  // FilePond
  public function tmpUplaod(Request $request)
  {
    if ($request->hasFile('photo')) {
      $file = $request->file('photo');
      $filename = $file->getclientOriginalName();
      // $folder = uniqid() . '-' . now()->timestamp;
      $folder = uniqid('post', true);
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