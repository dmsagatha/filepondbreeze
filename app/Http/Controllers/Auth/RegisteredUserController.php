<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\TemporaryFile;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

      // Se quita para FilePond
      // 'avatar'   => 'image'
    ]);

    $user = User::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => Hash::make($request->password)
    ]);

    // Laravel-medialibrary
    // $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');

    // FilePond
    $temporaryFile = TemporaryFile::where('folder', $request->avatar)->first();

    if ($temporaryFile) {
      $user->addMedia(storage_path('app/public/avatars/tmp/' . $request->avatar . '/' . $temporaryFile->filename))
          ->toMediaCollection('avatars');
      
      // Eliminar directorio y archivo temporal
      // rmdir(storage_path('app/public/avatars/tmp/' . $request->avatar));   
      File::deleteDirectory(storage_path('app/public/avatars/tmp/' . $request->avatar));

      // Eliminar el archivo temporal del modelo asociado
      $temporaryFile->delete();
    }

    // Laravel y Intervention Image
    /* if ($request->hasFile('avatar')) {
      $file = $request->file('avatar');
      $filename = $file->getclientOriginalName();
      $file->storeAs('avatars/' . $user->id, $filename);

      $image = Image::make(storage_path('app/public/avatars/' . $user->id . '/' . $filename))
          ->fit(50, 50)
          ->save(storage_path('app/public/avatars/' . $user->id . '/thumb-' . $filename));

      $user->update([
        'avatar' => $filename
      ]);
    } */

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
  }
}