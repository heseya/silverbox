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

    public function path()
    {
        return $this->owner . DIRECTORY_SEPARATOR . $this->name;
    }

    public function visibility()
    {
        return Storage::getVisibility($this->path());
    }

    public function mimeType()
    {
        return Storage::mimeType($this->path());
    }

    public function lastModified()
    {
        return Storage::lastModified($this->path());
    }

    public function size()
    {
        return Storage::size($this->path());
    }

    public function binary()
    {
        return Storage::get($this->path());
    }

    public function delete()
    {
        return Storage::delete($this->path());
    }

    public static function findOrFail(string $client, string $name)
    {
        if (!Storage::exists($client . DIRECTORY_SEPARATOR . $name)) {
            return abort(404, 'File not found');
        }

        return new self($client, $name);
    }

    public static function save(string $file, string $client, bool $private = false)
    {
        $file = Storage::putFile($client, $file, $private ? 'private' : 'public');

        return new self($client, ltrim($file, DIRECTORY_SEPARATOR . $client));
    }
}
