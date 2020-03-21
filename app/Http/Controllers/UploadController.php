<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use App\Http\Resources\FileResource;
use Laravel\Lumen\Routing\Controller as BaseController;

class UploadController extends BaseController
{
    /**
     * Files upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function upload(Request $request)
    {
        foreach ($request->allFiles() as $uploaded) {
            $files[] = File::save($uploaded, $request->client->name, isset($request->private));
        }

        return FileResource::collection($files ?? []);
    }
}
