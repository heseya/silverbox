<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class File
{
    public $name;
    public $owner;
    public $private;

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

    public static function find(string $client, string $name)
    {
        if (Storage::exists($client . DIRECTORY_SEPARATOR . $name)) {
            return new self($client, $name);
        }

        return false;
    }

    public static function save(string $file, string $client, bool $private = false)
    {
        $file = Storage::putFile($client, $file);
        Storage::setVisibility($file, $private ? 'private' : 'public');

        return new self($client, ltrim($file, DIRECTORY_SEPARATOR . $client));
    }
}
