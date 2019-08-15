<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;

    public $timestamps = false;

    public function __construct()
    {
        do {
            $this->id = str_random(32);

        } while (!empty(app('db')->select('SELECT `id` FROM files WHERE `id` = ?', [$this->id])));
    }

    public function path()
    {
        return storage_path(env('STORAGE_NAME', 'cdn') . '/' . $this->id);
    }
}
