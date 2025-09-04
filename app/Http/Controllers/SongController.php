<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancion;

class SongController extends Controller
{
	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'artist' => 'required|string|max:255',
			'photo' => 'nullable|string|max:255',
			'url' => 'required|string|max:255',
			'preview' => 'nullable|string',
		]);

		$cancion = Cancion::create([
			'title' => $validated['title'],
			'artist' => $validated['artist'],
			'photo' => $validated['photo'] ?? null,
			'url' => $validated['url'],
			'preview' => $validated['preview'] ?? null,
			'likes' => 0,
			'dislikes' => 0,
		]);

		return response()->json($cancion, 201);
	}
}
