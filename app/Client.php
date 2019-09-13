<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Client
{
    public $name, $token;

    public function __construct($name, $token = null)
    {
        $this->name = $name;

        if (is_null($token)) {
            $this->token = $this->generateToken();
        } else {
            $this->token = $token;
        }
    }

    private function generateToken() {
        return Str::random(512);
    }

    public function save() {
        return Storage::put($this->name . DIRECTORY_SEPARATOR . '.token', $this->token);
    }

    public static function login($name, $token)
    {
        if (! Storage::exists($name . DIRECTORY_SEPARATOR . '.token')) {
            return false;
        } elseif (Storage::get($name . DIRECTORY_SEPARATOR . '.token') !== $token) {
            return false;
        } else {
            return new Client($name, $token);
        }
    }
}
