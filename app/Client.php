<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Client
{
    public $name;
    public $key;

    public function __construct(string $name, ?string $key = null)
    {
        $this->name = $name;
        $this->key = $key ?? $this->generateKey();
    }

    private function generateKey(): string
    {
        return Str::random(512);
    }

    public function save()
    {
        return Storage::put($this->name . DIRECTORY_SEPARATOR . '.key', $this->key);
    }

    public static function login(string $name, string $key)
    {
        if (! Storage::exists($name . DIRECTORY_SEPARATOR . '.key')) {
            return;
        } elseif (Storage::get($name . DIRECTORY_SEPARATOR . '.key') !== $key) {
            return;
        }

        return new self($name, $key);
    }
}
