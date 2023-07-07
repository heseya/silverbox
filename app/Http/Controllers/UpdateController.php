<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;

class UpdateController extends Controller
{
    /**
     * @param  string  $client
     * @param  string  $fileName
     * @param  Request  $request
     * @return JsonResource
     */
    public function update(string $client, string $fileName, Request $request): JsonResource
    {
        $fileName = rawurldecode($fileName);
        $validator = Validator::make($request->all(), [
            'slug' => ['required', 'string', 'alpha_dash', 'max:100'],
        ]);

        if ($validator->fails()) {
            App::abort(422, 'Slug must be a string with max 100 characters.');
        }

        $file = File::findOrFail($client, $fileName);
        $slug = $request->input('slug');

        if (File::find($client, $slug . '.' . $file->extension())) {
            App::abort(422, 'Slug already taken.');
        }

        $file->move($slug);

        return FileResource::make($file);
    }
}
