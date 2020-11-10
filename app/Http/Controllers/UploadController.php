<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UploadController extends BaseController
{
    /**
     * Files upload.
     *
     * @param Request $request
     *
     * @throws Exception
     *
     * @return AnonymousResourceCollection
     */
    public function upload(Request $request)
    {
        $files = [];

        foreach ($request->allFiles() as $uploaded) {
            $files[] = retry(3, function () use ($uploaded, $request) {
                $file = File::save(
                    $uploaded,
                    $request->client->name,
                    (isset($request->private) && $request->private !== 'false'),
                );

                if (!Storage::exists($file->path())) {
                    throw new Exception('File cannot be uploaded.');
                }

                return $file;
            });
        }

        return FileResource::collection($files);
    }
}
