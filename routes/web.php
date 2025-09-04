
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/canciones', [SongController::class, 'store']);
Route::get('/canciones-list', function() { return \App\Models\Cancion::all();});

use App\Http\Controllers\UserController;
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/data', [UserController::class, 'user']);