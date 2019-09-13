<?php

namespace App\Http\Controllers;

use App\File;

class DeleteController extends Controller
{
    /**
     * Files remove.
     *
     * @return Response
     */
    public function delete($client, $fileId)
    {
        $file = File::find($client, $fileId);

        // File not found
        if (! $file) {
            return response()->json(['message' => 'not found'], 404);
        }

        $file->delete();

        return response()->json(null, 204);
    }
}
