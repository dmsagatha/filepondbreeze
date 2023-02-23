<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function getAvatar($userId)
  {
    $user = User::findOrFail($userId);

    return response()->download(
      storage_path('app/avatars/' . $userId . '/' . $user->avatar), 'avatar.png'
    );
  }
  
  // FilePond
  public function store(Request $request)
  {
    if ($request->hasFile('avatar')) {
      $file = $request->file('avatar');
      $filename = $file->getclientOriginalName();
      $folder =uniqid() . '-' . now()->timestamp;
      $file->storeAs('avatars/tmp/' . $folder, $filename);

      TemporaryFile::create([
        'folder' => $folder,
        'filename' => $filename
      ]);

      return $folder;
    }

    return '';
  }
}