<?php

namespace App\Http\Controllers;

use App\File;
use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    /**
     * Files view.
     *
     * @return Response
     */
    public function view(Request $request, $client, $fileId)
    {
        $file = File::find($client, $fileId);

        // File not found
        if (! $file) {
            return response()->json(['message' => 'not found'], 404);
        }

        // Private file
        if ($file->visibility() !== 'public') {
            $request->client = Client::login($request->client, $request->bearerToken());

            if (! $request->client) {
                return response()->json(['message' => 'unauthorized'], 401);
            }
        }

        return response($file->binary())->header('Content-Type', $file->mimeType());
    }

    /**
     * View info about file.
     *
     * @return Response
     */
    public function info($client, $fileId)
    {
        $file = File::find($client, $fileId);

        // File not found
        if (! $file) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json($file->info());
    }
}
