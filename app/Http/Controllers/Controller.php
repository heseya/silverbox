<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function info () {
        return response()->json([
            'system' => 'CDN',
            'author' => 'Jędrzej Buliński',
            'github' => 'https://github.com/bvlinsky/cdn'
        ]);
    }
}
