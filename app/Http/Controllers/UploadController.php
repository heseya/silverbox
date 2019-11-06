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
        foreach ($request->allFiles() as $uploaded) {
            $file = File::save($uploaded, $request->client->name, isset($request->private));
            $response[] = $file->info();
        }

        return response()->json($response ?? [], 201);
    }
}
