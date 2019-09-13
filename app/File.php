<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class File
{
    public $id;
    public $owner;
    public $file;

    public function __construct($client, $file, $id = null)
    {
        $this->owner = $client;
        $this->file = $file;

        if (is_null($id)) {
            $this->id = $this->generateId();
        } else {
            $this->id = $id;
        }
    }

    private function generateId()
    {
        do {
            $id = Str::random(32);
        } while (Storage::exists($this->owner . DIRECTORY_SEPARATOR . $id));

        return $id;
    }

    public function path()
    {
        return $this->owner . DIRECTORY_SEPARATOR . $this->id;
    }

    public function contentType()
    {
        return Storage::mimeType($this->path());
    }

    public function save()
    {
        return Storage::putFileAs($this->owner, $this->file, $this->id);
    }

    public function delete()
    {
        return Storage::delete($this->path());
    }

    public static function find($client, $id)
    {
        if (Storage::exists($client . DIRECTORY_SEPARATOR . $id)) {
            $file = Storage::get($client . DIRECTORY_SEPARATOR . $id);

            return new self($client, $file, $id);
        } else {
            return false;
        }
    }
}
