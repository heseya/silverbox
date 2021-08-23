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

    public function mimeType(): bool | string
    {
        return Storage::mimeType($this->path());
    }

    public function lastModified(): int
    {
        return Storage::lastModified($this->path());
    }

    public function size(): int
    {
        return Storage::size($this->path());
    }

    public function binary(): string
    {
        return Storage::get($this->path());
    }

    public function delete(): bool
    {
        return Storage::delete($this->path());
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
