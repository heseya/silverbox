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
            $file = new File($request->client->name, $uploaded);
            $file->save();
            $response[] = $file->info();
        }

        return response()->json($response ?? [], 201);
    }
}
