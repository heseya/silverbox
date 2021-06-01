<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;

class UploadController extends BaseController
{
    /**
     * Files upload.
     *
     * @param Request $request
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $files = [];

        foreach ($request->allFiles() as $uploaded) {
            $files[] = retry(3, function () use ($uploaded, $request) {
                $file = File::save(
                    $uploaded,
                    $request->client->name,
                    ($request->has('private') && $request->private !== 'false'),
                );

                if (!Storage::exists($file->path())) {
                    throw new Exception('File cannot be uploaded.');
                }

                return $file;
            });
        }

        return FileResource::collection($files)->response();
    }
}
