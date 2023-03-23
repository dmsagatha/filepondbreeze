<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

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
  }
  
  public function store(Request $request): RedirectResponse
  {
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
  }
}