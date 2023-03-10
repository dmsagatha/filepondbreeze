<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CategoryController extends Controller
{
  public function index(): View
  {
    return view('admon.categories.index', [
      'categories' => Category::latest()->get()
    ]);
  }
  
  public function create(): Response
  {
    return response()->view('categories.form');
  }
  
  public function store(Request $request): RedirectResponse
  {
    $validated = $request->validated();
  }
  
  public function show(Category $category): Response
  {
  }
  
  public function edit(Category $category): Response
  {
  }
  
  public function update(Request $request, Category $category): RedirectResponse
  {
  }
  
  public function destroy(Category $category): RedirectResponse
  {
  }
}