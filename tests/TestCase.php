<?php

use App\Client;
use App\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Application;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    public Client $client;

    public File $file;
    public File $filePrivate;

    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (Storage::exists('test')) {
            Storage::deleteDirectory('test');
        }

        $this->client = new Client('test');
        $this->client->save();

        $this->file = File::save(__DIR__ . '/files/image.jpeg', 'test', false);
        $this->filePrivate = File::save(__DIR__ . '/files/image.jpeg', 'test', true);
    }
}
