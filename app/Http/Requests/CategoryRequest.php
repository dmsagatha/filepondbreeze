<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }
  
  public function rules(): array
  {
    if (request()->routeIs('categories.store'))
    {
      $name = 'required|min:3|unique:categories';
    } else {
      $name = Rule::unique('users')->ignore($this->category);
    }

    return [
      'name'  => $name,
      'featured_image' => 'required|image|max:1024|mimes:jpg,jpeg,png'
    ];
  }
}