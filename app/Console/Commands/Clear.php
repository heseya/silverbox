<?php

namespace App\Console\Commands;

use App\File;
use Illuminate\Console\Command;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdn:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes files not associated with database';

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
        $start = microtime(true);
        $count = 0;

        $path = storage_path(env('STORAGE_NAME', 'cdn'));
        $files = scandir($path);

        foreach ($files as $file) {
            if (! preg_match('/\..*/', $file) && empty(File::find($file))) {
                $count++;
                unlink($path . '/' . $file);
            }
        }

        $this->line('<fg=green>Removed ' . $count . ' files in ' . round((microtime(true) - $start), 2) . ' seconds');
    }
}
