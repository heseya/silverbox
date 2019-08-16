<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function view($fileId)
    {
        $file = File::find($fileId);

        // File not found
        if(empty($file)) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response(file_get_contents($file->path()))
                ->header('Content-Type', mime_content_type($file->path()));
    }
}
