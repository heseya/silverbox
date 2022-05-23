<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class InfoController extends BaseController
{
    /**
     * View info about file.
     *
     * @param  string  $client
     * @param  string  $fileName
     * @return JsonResponse
     */
    public function info(string $client, string $fileName): JsonResponse
    {
        $file = File::findOrFail($client, $fileName);

        return FileResource::make($file)->response();
    }

    public function robots(): Response
    {
        return response("User-agent: *\ndisallow: /", Response::HTTP_OK)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
