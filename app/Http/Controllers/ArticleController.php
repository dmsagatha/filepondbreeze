<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  public function index(): View
  {
    return view('admon.articles.index', [
      'articles' => Article::latest()->get()
    ]);
  }
  
  public function create(): Response
  {
    return response()->view('admon.articles.create');
  }
  
  public function store(ArticleRequest $request): RedirectResponse
  {
    Article::create([
      'name'        => $request->name,
      'description' => $request->description,
      'imagen'      => $request->imagen,
    ]);

    return redirect(route('articles.index'))->with('success', 'Registro creado');
  }
  
  public function show(Article $article): Response
  {
  }
  
  public function edit(Article $article): Response
  {
  }
  
  public function update(Request $request, Article $article): RedirectResponse
  {
  }
  
  public function destroy(Article $article): RedirectResponse
  {
    $article->delete();

    $imagen_path = public_path('storage/uploads/'.$article->imagen);

    if (File::exists($imagen_path)) {
      unlink($imagen_path);
    }

    return redirect(route('articles.index'))->with('success', 'Registro eliminado');
  }
}