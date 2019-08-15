<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class UploadController extends Controller
{
    /**
     * Files upload
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        $files = [];

        foreach ($request->allFiles() as $uploaded) {

            $file = File::create();

            // Save file
            $uploaded->move(storage_path(env('STORAGE_NAME', 'cdn')), $file->id);

            $files[] = $file;
        }

        return response()->json($files, 201);
    }
}
