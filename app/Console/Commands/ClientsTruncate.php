<?php

namespace App\Console\Commands;

use App\Client;
use App\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientsTruncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:truncate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all client files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $name = Str::lower($this->argument('name'));

        if (!Storage::exists($name)) {
            $this->error('Client does not exist!');

            return;
        }

        $key = File::find($name, '.key');

        Storage::deleteDirectory($name);

        if ($key) {
            $client = new Client($name, $key->binary());
            $client->save();
        }

        $this->info('Client files removed');
    }
}
