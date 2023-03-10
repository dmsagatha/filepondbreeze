<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    if (request()->routeIs('products.store'))
    {
      $name = 'required|min:3|unique:products';
    } else {
      $name = Rule::unique('users')->ignore($this->product);
    }

    return [
      'name'  => $name,
      'description' => 'required'
    ];
  }
}