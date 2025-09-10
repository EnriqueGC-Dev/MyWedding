
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvitadoController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::post('/canciones', [SongController::class, 'store']);
    Route::post('/canciones/{id}/vote', [SongController::class, 'vote']);
});
Route::get('/canciones-list', function() {
    return \App\Models\Cancion::with('user:id,name')->get();
});

Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/data', [UserController::class, 'user']);


Route::post('/invitados', [InvitadoController::class, 'store']);