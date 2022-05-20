<?php

namespace App\Http\Controllers;

use App\Client;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewController extends BaseController
{
    /**
     * Files view.
     *
     * @param  Request  $request
     * @param  string  $client
     * @param  string  $fileName
     * @return Response
     */
    public function show(Request $request, string $client, string $fileName): Response|StreamedResponse
    {
        $file = File::findOrFail($client, $fileName);

        if ($file->visibility() !== 'public') {
            Client::loginOrFail($client, $request->header('x-api-key'));
        }

        if ($request->hasAny(['w', 'h', 'format']) && $file->isSupported()) {
            $ext = ($request->has('format') && $file->conversionSupported($request->input('format')))
                ? $request->input('format') : pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = pathinfo($fileName, PATHINFO_FILENAME);

            $size = ($request->hasAny(['w', 'h']) ? '_' : '') .
                ($request->has('w') ? 'w_' . $request->input('w') : '') .
                ($request->has('h') ? 'h_' . $request->input('h') : '');

            $cacheName = 'cache' . DIRECTORY_SEPARATOR . $fileName . $size . '.' . $ext;
            $cached = File::find($client, $cacheName);

            if (!$cached) {
                $image = Image::make($file->binary());

                if ($request->hasAny(['w', 'h'])) {
                    $image = $image
                        ->resize($request->input('w'), $request->input('h'), function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                }

                $image = $image->encode($ext);

                Storage::put($client . DIRECTORY_SEPARATOR . $cacheName, $image);

                $cached = File::find($client, $cacheName);
            }

            $file = $cached;
        }

        if (preg_match('/^nginx\/(\d+\.)?(\d+\.)?(\*|\d+)$/', $request->server('SERVER_SOFTWARE')))
        {
            return response(null)
                ->header('X-Accel-Redirect', "/stream/{$file->path()}")
                ->header('Content-Type', $file->mimeType());
        }

        return $file->isStreamable() ? $this->stream($request, $file)
            : response($file->binary())
                ->header('Content-Type', $file->mimeType())
                ->header('Cache-Control', 'public, max-age=' . env('CACHE_TIME', 15552000)); // default 6 months
    }

    private function stream(Request $request, File $file): Response|StreamedResponse
    {
        ob_get_clean();
        $buffer = 131072; // 128 KiB = 32 * 4096 bytes (standard disk allocation size)
        $streamPointer = $file->stream();
        $start = 0;
        $size = filesize($file->absolutePath());
        $end = $size - 1;
        $dynamicHeaders = [];

        if ($request->server('HTTP_RANGE')) {
            $request_end = $end;

            [, $range] = explode('=', $request->server('HTTP_RANGE'), 2);

            if ($range !== '-') {
                $range = explode('-', $range);
                $start = $range[0];

                $request_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $request_end;
            }
            $request_end = ($request_end > $end) ? $end : $request_end;

            $end = $request_end;

            fseek($streamPointer, $start);
            $dynamicHeaders['Content-Length'] = $end - $start + 1;
            $dynamicHeaders['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        } else {
            $dynamicHeaders['Content-Length'] = $size;
        }

        return response()->stream(
            function () use ($streamPointer, $buffer, $end) {
                set_time_limit(0);
                while (!feof($streamPointer) && ftell($streamPointer) <= $end) {
                    $bytesToRead = $buffer;
                    if ((ftell($streamPointer) + $bytesToRead) > $end) {
                        $bytesToRead = $end - ftell($streamPointer) + 1;
                    }
                    echo fread($streamPointer, $bytesToRead);
                    flush();
                }
            },
            Response::HTTP_PARTIAL_CONTENT,
            array_merge(
                $dynamicHeaders,
                [
                    'Accept-Ranges' => "0-{$end}",
                    'Content-Type' => $file->mimeType(),
                ]
            )
        );
    }
}
