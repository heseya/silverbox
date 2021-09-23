<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientsRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:remove {name} {--f|with-files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove client';

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

        if ($this->option('with-files')) {
            Storage::deleteDirectory($name);
        } else {
            Storage::delete($name . DIRECTORY_SEPARATOR . '.key');
        }

        $this->info('Client removed');
    }
}
