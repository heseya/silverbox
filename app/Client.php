<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name'];

    public function __construct($name)
    {
        $this->name = $name;
        $this->token = str_random(255);
    }
}
