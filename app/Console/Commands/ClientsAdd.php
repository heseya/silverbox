<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;

class ClientsAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:add {name?}';

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
        if (empty($this->argument('name'))) {
            $this->error('You must provide a name!');

            return;
        }

        $client = new Client($this->argument('name'));

        if (!$client->save()) {
            $this->error('Something went wrong!');

            return;
        }

        $this->info('New client added!');
        $this->line('<fg=yellow>Name:</> ' . $client->name);
        $this->line('<fg=yellow>API Key:</> ' . $client->key . "\n");
    }
}
