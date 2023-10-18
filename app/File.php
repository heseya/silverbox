<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class File
{
    public string $name;
    public string $owner;

    public function __construct(string $client, string $name)
    {
        $this->owner = $client;
        $this->name = $name;
    }

    public function path(): string
    {
        return $this->owner . DIRECTORY_SEPARATOR . $this->name;
    }

    public function visibility(): string
    {
        return Storage::getVisibility($this->path());
    }

    public function mimeType(): bool|string
    {
        return Storage::mimeType($this->path());
    }

    public function extension(): string
    {
        return pathinfo($this->path(), PATHINFO_EXTENSION);
    }

    public function lastModified(): int
    {
        return Storage::lastModified($this->path());
    }

    public function isSupported(): bool
    {
        return in_array($this->mimeType(), [
            'image/webp',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
        ]);
    }

    public function isStreamable(): bool
    {
        return in_array($this->mimeType(), [
            'video/avi',
            'video/divx',
            'video/mp4',
            'video/mp4v-es',
            'video/mpeg',
            'video/mpeg-system',
            'video/quicktime',
            'video/webm',
            'video/x-avi',
            'video/x-mpeg2',
        ]);
    }

    public function isRaster(): bool
    {
        return in_array($this->mimeType(), [
            'image/avif',
            'image/webp',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
        ]);
    }

    public function conversionSupported(string $format): bool
    {
        $avif = function_exists('imageavif') ? ['avif'] : [];
        return in_array($format, array_merge($avif, [
            'webp',
            'jpeg',
            'png',
            'auto',
        ]));
    }

    public function size(): int
    {
        return Storage::size($this->path());
    }

    public function binary(): string
    {
        return Storage::get($this->path());
    }

    public function stream()
    {
        return Storage::readStream($this->path());
    }

    public function absolutePath(): string
    {
        return Storage::path($this->path());
    }

    public function delete(): bool
    {
        return Storage::delete($this->path());
    }

    public function move($slug): void
    {
        $slug .= '.' . $this->extension();
        Storage::move($this->path(), $this->owner . DIRECTORY_SEPARATOR . $slug);

        $this->name = $slug;
    }

    public static function find(string $client, string $name): self|bool
    {
        if (Storage::exists($client . DIRECTORY_SEPARATOR . $name)) {
            return new self($client, $name);
        }

        return false;
    }

    public static function findOrFail(string $client, string $name): self
    {
        return self::find($client, $name) ?: abort(404, 'File not found');
    }

    public static function save(string $file, string $client, bool $private = false): self
    {
        $file = Storage::putFile($client, $file, $private ? 'private' : 'public');

        return new self($client, ltrim($file, DIRECTORY_SEPARATOR . $client));
    }
}
