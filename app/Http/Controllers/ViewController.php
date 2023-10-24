<?php

namespace App\Http\Controllers;

use App\Client;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
     * @return Response|StreamedResponse
     */
    public function show(Request $request, string $client, string $fileName): Response|StreamedResponse
    {
        $fileName = rawurldecode($fileName);
        $file = File::findOrFail($client, $fileName);

        if ($file->visibility() !== 'public') {
            Client::loginOrFail($client, $request->header('x-api-key'));
        }

        if ($request->hasAny(['w', 'h', 'format']) && $file->isSupported()) {
            $ext = $this->prepareExtension($request, $file, $fileName);
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

        return $file->isStreamable() ? $this->stream($request, $file)
            : response($file->binary())
                ->header('Content-Type', $file->mimeType())
                ->header('Cache-Control', 'public, max-age=' . env('CACHE_TIME', 15552000)); // default 6 months
    }

    private function prepareExtension(Request $request, File $file, string $fileName): string
    {
        if ($request->has('format') && $file->conversionSupported($request->input('format'))) {
            return match ($request->input('format')) {
                'auto' => $this->autoExtension($request, $file, $fileName),
                default => $request->input('format'),
            };
        }

        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    private function autoExtension(Request $request, File $file, string $fileName): string
    {
        if ($file->isRaster() && $request->header('Accept')) {
            foreach ($this->formatsPriority() as $format) {
                if (Str::contains($request->header('Accept'), $format)) {
                    return Str::afterLast($format, '/');
                }
            }
        }

        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    private function stream(Request $request, File $file): StreamedResponse
    {
        $this->cleanAllOutputBuffers();

        $bufferSize = 131072; // 128 KiB = 32 * 4096 bytes (standard disk allocation size)
        $streamPointer = $file->stream();
        $size = filesize($file->absolutePath());
        $start = 0;
        $end = $size - 1;

        $responseType = Response::HTTP_OK;
        $dynamicHeaders = [
            'Content-Length' => $size,
        ];

        if ($request->server('HTTP_RANGE')) {
            [$unit, $range] = explode('=', $request->server('HTTP_RANGE'), 2) + ['', ''];

            // At least semi valid range header
            if ($unit === 'bytes' && $range !== '') {
                // Only stream first range because mutlipart/byteranges is hard
                [$firstRange] = explode(',', $range, 2);
                [$rangeStart, $rangeEnd] = explode('-', $firstRange, 2) + ['', ''];

                // Proper data range calculation
                if ($rangeStart !== '') {
                    // clamp range start between 0 and end of file
                    $start = min($end, max(0, (int) $rangeStart));

                    if ($rangeEnd !== '') {
                        // clamp range end between range start and end of file
                        $end = min($end, max($start, (int) $rangeEnd));
                    }
                } elseif ($rangeEnd !== '') {
                    // get range of last n bytes
                    $start = $size - (int) $rangeEnd;
                }

                fseek($streamPointer, $start);
                $dynamicHeaders = [
                    'Content-Length' => $end - $start + 1,
                    'Content-Range' => "bytes {$start}-{$end}/{$size}",
                ];

                $responseType = Response::HTTP_PARTIAL_CONTENT;
            }
        }

        return response()->stream(
            function () use ($streamPointer, $bufferSize, $end) {
                while (!feof($streamPointer) && ftell($streamPointer) <= $end) {
                    echo fread($streamPointer, $bufferSize);
                    flush();
                }
            },
            $responseType,
            $dynamicHeaders + [
                'Accept-Ranges' => 'bytes',
                'Content-Type' => $file->mimeType(),
                'Cache-Control' => 'public, max-age=' . env('CACHE_TIME', 15552000),
            ]
        );
    }

    private function cleanAllOutputBuffers()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    private function formatsPriority(): array
    {
        return [
            'image/webp',
            'image/png',
            'image/jpeg',
        ];
    }
}
