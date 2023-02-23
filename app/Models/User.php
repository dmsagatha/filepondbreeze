<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
  use InteractsWithMedia;

  protected $fillable = [
    'name',
    'email',
    'password',
    'avatar',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

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