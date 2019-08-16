<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;

class ClientAdd extends Command
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
    protected $description = 'Add a new CDN client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = Client::create();
        
        if (! empty($this->argument('name'))) {
            $client->update(['name' => $this->argument('name')]);
        }

        $this->line('<fg=green>New client added!</>');
        $this->line('<fg=yellow>ID:</> ' . $client->id);
        $this->line('<fg=yellow>Name:</> ' . $client->name);
        $this->line('<fg=yellow>Token:</> ' . $client->token . "\n");
    }
}
