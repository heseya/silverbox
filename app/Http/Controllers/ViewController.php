<?php

namespace App\Http\Controllers;

use App\Client;
use App\File;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Laravel\Lumen\Routing\Controller as BaseController;

class ViewController extends BaseController
{
    /**
     * Files view.
     *
     * @param string $client
     * @param string $fileName
     *
     * @return Response
     */
    public function view(Request $request, string $client, string $fileName)
    {
        $file = File::find($client, $fileName);

        // File not found
        if (!$file) {
            return abort(404);
        }

        // Private file
        if ($file->visibility() !== 'public') {
            $request->client = Client::login($request->client, $request->header('Authorization'));

            if (!$request->client) {
                return abort(403);
            }
        }

        if ($file->mimeType() !== 'image/svg+xml' && ($request->has('w') || $request->has('h'))) {
            $response = Image::cache(function ($image) use ($file, $request) {
                return $image->make($file->binary())
                ->resize($request->input('w'), $request->input('h'), function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            });
        }

        return response($response ?? $file->binary())
            ->header('Content-Type', $file->mimeType());
    }

    /**
     * View info about file.
     *
     * @param mixed $client
     * @param mixed $fileName
     *
     * @return Response
     */
    public function info(string $client, string $fileName)
    {
        $file = File::find($client, $fileName);

        // File not found
        if (!$file) {
            return abort(404);
        }

        return new FileResource($file);
    }
}
