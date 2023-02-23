<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
  public function create(): View
  {
    return view('auth.register');
  }
  
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name'     => ['required', 'string', 'max:255'],
      'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'avatar'   => 'image'
    ]);

    $user = User::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => Hash::make($request->password),
      'avatar'   => !empty($filename) ? $filename : 'default_avatar.png'
    ]);

    if ($request->hasFile('avatar')) {
      $file = $request->file('avatar');
      $filename = $file->getclientOriginalName();
      $file->storeAs('avatars/' . $user->id, $filename);
      // $file->storeAs('avatars/' . $user->id, $filename, 'public');
      // $file->storeAs('avatars/' . auth()->id(), $filename, 'public');
      // $file->storeAs('avatars/' . auth()->id(), $filename);

      $image = Image::make(storage_path('app/public/avatars/' . $user->id . '/' . $filename))
          ->fit(50, 50)
          ->save(storage_path('app/public/avatars/' . $user->id . '/thumb-' . $filename));

      $user->update([
        'avatar' => $filename
      ]);
    }

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
  }
}