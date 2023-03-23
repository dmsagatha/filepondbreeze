<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
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
    Article::create($request->all());

    Session()->flash('statusCode', 'success');

    return redirect(route('articles.index'))->withStatus('Registro creado');
  }
  
  public function show(Article $article)
  {
  }
  
  public function edit(Article $article): Response
  {
    return response()->view('admon.articles.edit', compact('article'));
  }
  
  public function update(ArticleRequest $request, Article $article): RedirectResponse
  {
    $article->update($request->all());

    Session()->flash('statusCode', 'info');

    return redirect(route('articles.index'))->withStatus('Registro atualizado');
  }
  
  public function destroy(Article $article): RedirectResponse
  {
    $article->delete();

    // Eliminar la imagen
    $imagen_path = public_path('uploads/' . $article->imagen);

    if (File::exists($imagen_path)) {
      unlink($imagen_path);
    }

    Session()->flash('statusCode', 'warning');

    return to_route('articles.index')->withStatus('Registro eliminado permanentemente!.');
  }
}