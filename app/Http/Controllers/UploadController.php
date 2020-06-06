<?php

namespace App\Http\Controllers;

use App\File;
use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;

class UploadController extends BaseController
{
    /**
     * Files upload.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        $files = [];

        foreach ($request->allFiles() as $uploaded) {
            $files[] = retry(3, function () use ($uploaded, $request) {
                $file = File::save(
                    $uploaded,
                    $request->client->name,
                    (isset($request->private) && $request->private != false)
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
