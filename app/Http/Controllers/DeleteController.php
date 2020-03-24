<?php

namespace App\Http\Controllers;

use App\File;
use Laravel\Lumen\Routing\Controller as BaseController;

class DeleteController extends BaseController
{
    /**
     * Files remove.
     *
     * @param mixed $client
     * @param mixed $fileName
     *
     * @return Response
     */
    public function delete(string $client, string $fileName)
    {
        $file = File::find($client, $fileName);

        // File not found
        if (!$file) {
            return abort(404);
        }

        $file->delete();

        return response()->json(null, 204);
    }
}
