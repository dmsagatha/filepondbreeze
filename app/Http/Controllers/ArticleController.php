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
      'image'       => $request->image,
    ]);

    return redirect(route('articles.index'))->with('success', 'Registro creado');
  }
  
  public function show(Article $article)
  {
  }
  
  public function edit(Article $article)
  {
  }
  
  public function update(Request $request, Article $article)
  {
  }
  
  public function destroy(Article $article): RedirectResponse
  {
    $article->delete();

    $image_path = public_path('storage/uploads/'.$article->image);

    if (File::exists($image_path)) {
      unlink($image_path);
    }

    return redirect(route('articles.index'))->with('success', 'Registro eliminado');
  }
}