<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function index(): Response
  {
    return response()->view('admon.posts.index', [
      'posts' => Post::latest()->get()
    ]);
  }
  
  public function store(Request $request): RedirectResponse
  {
    /* $request->validate([
      'title' => ['required', 'unique:' . Post::class]
    ]); */
    $validator = Validator::make($request->all(), [
      'title' => ['required', 'unique:posts']
    ]);

    // FilePond
    $temporaryFile = TemporaryFile::where('folder', $request->photo)->first();

    if ($validator->fails() && $temporaryFile) {
      Storage::deleteDirectory('posts/tmp/' . $temporaryFile->folder);
      $temporaryFile->delete();

      return to_route('posts.index')->withErrors($validator)->withInput();
    } elseif ($validator->fails()) {
      return to_route('posts.index')->withErrors($validator)->withInput();
    }

    if ($temporaryFile) {
      Storage::copy('posts/tmp/' . $temporaryFile->folder . '/' . $temporaryFile->filename, 'posts/' . $temporaryFile->folder . '/' . $temporaryFile->filename);

      Post::create([
        'title' => $request->title,
        'photo' => $temporaryFile->folder . '/' . $temporaryFile->filename
      ]);
      
      // Eliminar directorio y archivo temporal
      File::deleteDirectory(storage_path('app/public/posts/tmp/' . $request->photo));
      // Storage::deleteDirectory('posts/tmp/' . $temporaryFile->folder);

      // Eliminar el archivo temporal del modelo asociado
      $temporaryFile->delete();

      return to_route('posts.index')->with('success', 'Registro creado');
    }

    return to_route('posts.index')->with('danger', 'Por favor subir un archivo');
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

  public function tmpDelete()
  {
    $temporaryFile = TemporaryFile::where('folder', request()->getContent())->first(); 
    
    if ($temporaryFile) {
      Storage::deleteDirectory('posts/tmp/' . $temporaryFile->folder);
      $temporaryFile->delete();

      return response('');
    }
  }

  public function edit(Post $post): Response
  {
    return response()->view('admon.posts.edit', [
      'post' => $post,
      'featured_image' => explode(',', $post->featured_image)
    ]);
  }

  public function update(Request $request, Post $post): RedirectResponse
  {
    $validator = Validator::make($request->all(), [
      'title' => ['required', 'unique:posts']
    ]);

    // FilePond
    $temporaryFile = TemporaryFile::where('folder', $request->photo)->first();

    if ($validator->fails() && $temporaryFile) {
      Storage::deleteDirectory('posts/tmp/' . $temporaryFile->folder);
      $temporaryFile->delete();

      return to_route('posts.index')->withErrors($validator)->withInput();
    } elseif ($validator->fails()) {
      return to_route('posts.index')->withErrors($validator)->withInput();
    }

    if ($temporaryFile) {
      Storage::copy('posts/tmp/' . $temporaryFile->folder . '/' . $temporaryFile->filename, 'posts/' . $temporaryFile->folder . '/' . $temporaryFile->filename);

      // Post::create([
      //   'title' => $request->title,
      //   'photo' => $temporaryFile->folder . '/' . $temporaryFile->filename
      // ]);

      $post->photo = $temporaryFile->folder . '/' . $temporaryFile->filename;

      $post->update($request->$post);

      // Eliminar directorio y archivo temporal
      File::deleteDirectory(storage_path('app/public/posts/tmp/' . $request->photo));
      // Storage::deleteDirectory('posts/tmp/' . $temporaryFile->folder);

      // Eliminar el archivo temporal del modelo asociado
      $temporaryFile->delete();

      return to_route('posts.index')->with('success', 'Registro actualizado');
    }

    return to_route('posts.index')->with('danger', 'Por favor subir un archivo');
  }

  public function destroy(Post $post): RedirectResponse
  {
    $post->delete();
    $imagen_path = public_path('storage/posts/' . $post->photo);
    // $imagen_path = storage_path('app/public/posts/' . $post->photo);

    if (File::exists($imagen_path)) {
      unlink($imagen_path);
    }

    return redirect(route('posts.index'))->with('danger', 'Registro eliminado');
  }
}