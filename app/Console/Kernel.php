<?php

namespace App\Console;

use App\Console\Commands\ClientsAdd;
use App\Console\Commands\ClientsRemove;
use App\Console\Commands\ClientsShow;
use App\Console\Commands\ClientsTruncate;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ClientsAdd::class,
        ClientsRemove::class,
        ClientsShow::class,
        ClientsTruncate::class,
    ];
}
