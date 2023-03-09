<?php

namespace App\Models;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  protected $fillable = [
    'name',
    'description',
  ];

  public function photos()
  {
    return $this->morphMany(Media::class, 'model');
  }

  /* public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb')
        ->width(50)
        ->height(50)
        ->sharpen(10);
  } */
}