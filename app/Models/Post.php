<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'photo'];

  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb-50')
      ->width(50)
      ->height(50);

    $this->addMediaConversion('thumb-100')
      ->width(100)
      ->height(100);
  }
}