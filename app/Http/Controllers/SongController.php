<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancion;

class SongController extends Controller
{
	/**
	 * Devuelve el campo preview de Deezer para un deezer_id
	 */
	public function deezerPreview($deezer_id)
	{
		$url = "https://api.deezer.com/track/" . $deezer_id;
		try {
			$response = file_get_contents($url);
			$data = json_decode($response, true);
			if (isset($data['preview'])) {
				return response()->json(['preview' => $data['preview']]);
			} else {
				return response()->json(['error' => 'Preview no encontrado'], 404);
			}
		} catch (\Exception $e) {
			return response()->json(['error' => 'Error al consultar Deezer'], 500);
		}
	}
	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'artist' => 'required|string|max:255',
			'photo' => 'nullable|string|max:255',
			'url' => 'required|string|max:255',
			'deezer_id' => 'required|integer',
		]);

		$user = $request->user();
		if (!$user) {
			return response()->json(['error' => 'No autenticado'], 401);
		}

		$cancion = Cancion::create([
			'user_id' => $user->id,
			'title' => $validated['title'],
			'artist' => $validated['artist'],
			'photo' => $validated['photo'] ?? null,
			'url' => $validated['url'],
			'deezer_id' => $validated['deezer_id'],
			'likes' => 0,
			'dislikes' => 0,
		]);

		return response()->json($cancion, 201);
	}

    	public function vote(Request $request, $id)
	{
		$user = $request->user();
		if (!$user) {
			return response()->json(['error' => 'No autenticado'], 401);
		}
		$cancion = Cancion::findOrFail($id);
		$userId = $user->id;
		$user_like = $cancion->user_like ?? [];
		$user_dislike = $cancion->user_dislike ?? [];
		$action = $request->input('action');

		if ($action === 'like') {
			if (!in_array($userId, $user_like)) {
				$user_like[] = $userId;
				$user_dislike = array_diff($user_dislike, [$userId]);
			} else {
				// Si ya votó like, lo quitamos
				$user_like = array_diff($user_like, [$userId]);
			}
		} elseif ($action === 'dislike') {
			if (!in_array($userId, $user_dislike)) {
				$user_dislike[] = $userId;
				$user_like = array_diff($user_like, [$userId]);
			} else {
				// Si ya votó dislike, lo quitamos
				$user_dislike = array_diff($user_dislike, [$userId]);
			}
		} elseif ($action === 'switch_to_like') {
			$user_like[] = $userId;
			$user_dislike = array_diff($user_dislike, [$userId]);
		} elseif ($action === 'switch_to_dislike') {
			$user_dislike[] = $userId;
			$user_like = array_diff($user_like, [$userId]);
		}

		$cancion->user_like = array_values($user_like);
		$cancion->user_dislike = array_values($user_dislike);
		$cancion->likes = count($cancion->user_like);
		$cancion->dislikes = count($cancion->user_dislike);
		$cancion->save();

		return response()->json(['success' => true, 'likes' => $cancion->likes, 'dislikes' => $cancion->dislikes]);
	}
}
