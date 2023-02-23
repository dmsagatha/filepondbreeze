<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
  }
}