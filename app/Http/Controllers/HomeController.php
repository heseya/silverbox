<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function info()
    {
        return response()->json([
            'system' => 'CDN',
            'author' => 'Jędrzej Buliński',
            'github' => 'https://github.com/bvlinsky/cdn',
        ]);
    }
}
