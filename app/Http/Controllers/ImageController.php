<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Serves images from private storage to the public.
     * Essential for hosting like InfinityFree where symlinks are disabled.
     */
public function show($path)
{
    // Clean the path
    $cleanPath = str_replace('public/storage/', '', $path);

    // Get the absolute path to the file
    $fullPath = storage_path('app/public/' . $cleanPath);

    // Check if the file exists using the physical path
    if (!file_exists($fullPath)) {
        abort(404, 'Image not found.');
    }

    // response()->file() is much faster and uses less memory
    // It automatically handles MimeTypes and Headers for you!
    return response()->file($fullPath);
}
}