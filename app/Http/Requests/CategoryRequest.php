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
      // $name = 'required|unique:categories,name,' . $this->route('category')->id;
      // $name = Rule::unique('categories')->ignore($this->category);
      $name = Rule::unique('categories')->ignore($this->route('category'));
    }

    return [
      'name' => $name,
      // 'featured_image' => 'required|image',
      'featured_image' => 'required',
    ];
  }
}