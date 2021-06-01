<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Client
{
    public string $name;
    public string $key;

    public function __construct(string $name, ?string $key = null)
    {
        $this->name = $name;
        $this->key = $key ?? $this->generateKey();
    }

    private function generateKey(): string
    {
        return Str::random(512);
    }

    public function save(): bool
    {
        return Storage::put(
            $this->name . DIRECTORY_SEPARATOR . '.key',
            Hash::make($this->key),
            'private',
        );
    }

    public static function loginOrFail(string $name, ?string $key): self
    {
        $file = File::findOrFail($name, '.key');

        if (!Hash::check($key, $file->binary())) {
            abort(401, 'API key is missing or invalid');
        }

        return new self($name, $key);
    }
}
