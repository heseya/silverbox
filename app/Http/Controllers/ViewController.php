<?php

namespace App\Http\Controllers;

use App\File;

class ViewController extends Controller
{
    /**
     * Files view.
     *
     * @return Response
     */
    public function view($client, $fileId)
    {
        $file = File::find($client, $fileId);

        // File not found
        if (! $file) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response($file->file)->header('Content-Type', $file->contentType());
    }
}
