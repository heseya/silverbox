<?php

namespace App\Console\Commands;

use App\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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

        foreach (Storage::files() as $file) {
            if (empty(File::find($file))) {
                $count++;
                Storage::delete($file);
            }
        }

        $this->line('<fg=green>Removed ' . $count . ' files in ' . round((microtime(true) - $start), 2) . ' seconds');
    }
}
