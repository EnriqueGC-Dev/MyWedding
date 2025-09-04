
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/canciones', [SongController::class, 'store']);
Route::get('/canciones-list', function() { return \App\Models\Cancion::all();});