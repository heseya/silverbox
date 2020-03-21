<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class File
{
    public $id;
    public $owner;
    public $private;

    public function __construct($client, $id = null)
    {
        $this->owner = $client;
        $this->id = $id ?? $this->generateId();
    }

    public function path()
    {
        return $this->owner . DIRECTORY_SEPARATOR . $this->id;
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

    public static function find($client, $id)
    {
        if (Storage::exists($client . DIRECTORY_SEPARATOR . $id)) {
            return new self($client, $id);
        }

        return false;
    }

    public static function save($file, $client, $private = false)
    {
        $file = Storage::putFile($client, $file);
        Storage::setVisibility($file, $private ? 'private' : 'public');

        return new self($client, ltrim($file, DIRECTORY_SEPARATOR . $client));
    }
}
