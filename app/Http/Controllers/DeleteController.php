<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteController extends Controller
{
    /**
     * Files remove.
     *
     * @return Response
     */
    public function delete($fileId)
    {
        $file = File::find($fileId);

        // File not found
        if (empty($file)) {
            return response()->json(['message' => 'not found'], 404);
        }

        Storage::delete($file->id);
        $file->delete();

        return response()->json(null, 204);
    }
}
