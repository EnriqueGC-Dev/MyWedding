<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UploadMediaController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg',
        ]);
        $uploaded = [];
        foreach ($request->file('files', []) as $file) {
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/uploads'), $filename);
            $uploaded[] = $filename;
        }
        return response()->json(['success' => true, 'files' => $uploaded]);
    }
}
