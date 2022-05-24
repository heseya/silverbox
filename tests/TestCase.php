<?php

use App\Client;
use App\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Laravel\Lumen\Application;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    public Client $client;

    public File $file;
    public File $filePrivate;
    public File $fileSvg;
    public File $filePng;
    public File $fileVideo;

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
        $this->fileSvg = File::save(__DIR__ . '/files/image.svg', 'test', false);
        $this->filePng = File::save(__DIR__ . '/files/image.png', 'test', false);
        $this->fileVideo = File::save(__DIR__ . '/files/video.mp4', 'test', false);
    }

    public function send(string $method, string $uri, array $parameters = [], array $headers = []): Response|TestResponse
    {
        $server = $this->transformHeadersToServerVars($headers);

        return $this->call($method, $uri, $parameters, [], [], $server);
    }
}
