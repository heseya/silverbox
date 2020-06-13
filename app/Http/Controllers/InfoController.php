<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use Laravel\Lumen\Routing\Controller as BaseController;

class InfoController extends BaseController
{
    /**
     * View info about file.
     *
     * @param string $client
     * @param string $fileName
     *
     * @return FileResource
     */
    public function info(string $client, string $fileName)
    {
        $file = File::findOrFail($client, $fileName);

        return FileResource::make($file);
    }
}
