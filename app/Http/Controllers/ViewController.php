<?php

namespace App\Http\Controllers;

use App\File;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\FileResource;
use Laravel\Lumen\Routing\Controller as BaseController;

class ViewController extends BaseController
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
            return abort(404);
        }

        // Private file
        if ($file->visibility() !== 'public') {
            $request->client = Client::login($request->client, $request->header('Authorization'));

            if (! $request->client) {
                return abort(403);
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
            return abort(404);
        }

        return new FileResource($file);
    }
}
