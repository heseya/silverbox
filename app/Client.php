<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name'];

    public function __construct()
    {
        $this->token = str_random(512);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
