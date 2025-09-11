<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FotosController extends Controller
{
    public function index()
    {
        $dir = public_path('images/uploads');
        $files = collect(File::files($dir))
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            })
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'url' => asset('images/uploads/' . $file->getFilename()),
                    'type' => in_array(strtolower($file->getExtension()), ['mp4','webm','ogg']) ? 'video' : 'image',
                    'mtime' => $file->getMTime(),
                ];
            })
            ->values();
        return response()->json($files);
    }
}
