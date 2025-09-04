<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function signup(Request $request)
	{
		$request->validate([
			'email' => 'required|email|unique:users,email',
			'name' => 'required',
			'password' => 'required|min:6',
		]);
		$user = User::create([
			'email' => $request->email,
			'name' => $request->name,
			'password' => Hash::make($request->password),
		]);
		Auth::login($user);
		return response()->json(['status' => 'OK', 'success' => true, 'user' => $user]);
	}

	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');
		if (Auth::attempt($credentials)) {
			$user = Auth::user();
			return response()->json(['status' => 'OK', 'success' => true, 'user' => $user]);
		}
		return response()->json(['status' => 'ERROR', 'success' => false, 'message' => 'Credenciales incorrectas'], 401);
	}

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return response()->json([
            'status' => 'OK'
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        if ($user instanceof User) {
            $json = array_merge(
                ['status' => 'OK'],
                $user->toArray(),
            );
        } else {
            $json = [
                'status' => 'ERROR',
                'server' => $_SERVER,
                'cookies' => $_COOKIE,
                'environment' => $_ENV
            ];
        }

        return response()->json($json);
    }
}
