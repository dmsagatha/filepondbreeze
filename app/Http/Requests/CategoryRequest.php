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
      $name  = 'required|min:3|unique:categories';
      $image = 'required';
    } else {
      // $name = 'min:3|required|unique:categories,name,' . $this->route('category')->id;
      // $name = ['required', 'min:3', Rule::unique('categories')->ignore($this->category)];
      $name  = ['required', 'min:3', Rule::unique('categories')->ignore($this->route('category'))];
      $image = '';
    }

    return [
      'name'           => $name,
      'featured_image' => $image,
    ];
  }
}