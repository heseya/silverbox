<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ClientsAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:add {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new client';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $name = Str::lower($this->argument('name'));

        $client = new Client($name);

        if (!$client->save()) {
            $this->error('Something went wrong!');
            return;
        }

        $this->info('New client added!');
        $this->line('<fg=yellow>Name:</> ' . $client->name);
        $this->line('<fg=yellow>API Key:</> ' . $client->key . "\n");
    }
}
