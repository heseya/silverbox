<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClientsShow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of clients';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $clients = [];

        foreach (Storage::directories() as $directory) {
            $size = 0;

            foreach (Storage::allFiles($directory) as $file) {
                $size += Storage::size($file);
            }

            $clients[] = [
                $directory,
                number_format($size / 1048576, 2) . ' MB',
                Storage::exists($directory . DIRECTORY_SEPARATOR . '.key') ? 'yes' : 'no',
            ];
        }

        $this->table(['client', 'size', 'key'], $clients);
    }
}
