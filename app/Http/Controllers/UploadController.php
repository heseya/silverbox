<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Files upload.
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        $files = [];

        foreach ($request->allFiles() as $uploaded) {

            $file = $request->client->files()->save(new File());

            // Save file
            $uploaded->move(storage_path(env('STORAGE_NAME', 'cdn')), $file->id);

            $files[] = $file;
        }

        return response()->json($files, 201);
    }
}
