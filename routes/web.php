<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvitadoController;
use App\Http\Controllers\FotosController;

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

Route::get('/media-list', [FotosController::class, 'index']);

Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/data', [UserController::class, 'user']);


Route::post('/invitados', [InvitadoController::class, 'store']);

Route::get('/{any}', function () {
    return view('welcome');
});