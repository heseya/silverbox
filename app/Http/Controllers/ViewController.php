<?php

namespace App\Http\Controllers;

use App\Client;
use App\File;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = pathinfo($fileName, PATHINFO_FILENAME);

            $size = '_' . ($request->has('w') ? 'w_' . $request->input('w') : '') .
                ($request->has('h') ? 'h_' . $request->input('h') : '');

            $cacheName = 'cache' . DIRECTORY_SEPARATOR . $fileName . $size . '.' . $ext;
            $cached = File::find($client, $cacheName);

            if (!$cached) {
                $image = Image::make($file->binary())
                ->resize($request->input('w'), $request->input('h'), function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode();
                
                Storage::put($client . DIRECTORY_SEPARATOR . $cacheName, $image);

                $cached = File::find($client, $cacheName);
            }

            $file = $cached;
        }

        return response($file->binary())
            ->header('Content-Type', $file->mimeType())
            ->header('Cache-Control', 'public, max-age=' . env('CACHE_TIME', 15552000)); // default 6 months
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
