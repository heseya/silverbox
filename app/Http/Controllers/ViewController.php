<?php

namespace App\Http\Controllers;

use App\Client;
use App\File;
use App\Http\Resources\FileResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Laravel\Lumen\Routing\Controller as BaseController;

class ViewController extends BaseController
{
    /**
     * Files view.
     *
     * @param Request $request
     * @param string $client
     * @param string $fileName
     *
     * @return Response
     */
    public function view(Request $request, string $client, string $fileName)
    {
        $file = File::findOrFail($client, $fileName);

        // Private file
        if ($file->visibility() !== 'public') {
            Client::loginOrFail($request->client, $request->header('x-api-key'));
        }

        // Image manipulation
        if (isset($request->w) || isset($request->h)) {
            $response = Image::cache(function ($image) use ($file, $request) {
                return $image
                    ->make($file->binary())
                    ->resize($request->w, $request->h, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
            });
        }

        return response($response ?? $file->binary())
            ->header('Content-Type', $file->mimeType());
    }
}
