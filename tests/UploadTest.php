<?php

use App\Client;
use Illuminate\Http\UploadedFile;

class UploadTest extends TestCase
{
    public function testUploadPublicFile(): void
    {
        $client = new Client('test');
        $client->save();

        $file = UploadedFile::fake()->image('test.jpg');

        $this->post(
            '/test',
            ['file' => $file],
            ['x-api-key' => $client->key],
        );

        $this->assertResponseOk();
    }

    public function testUploadPrivateFile(): void
    {
        $client = new Client('test');
        $client->save();

        $file = UploadedFile::fake()->image('test.jpg');

        $this->post(
            '/test?private',
            ['file' => $file],
            ['x-api-key' => $client->key],
        );

        $this->assertResponseOk();
    }

    public function testUploadUnauthorized(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $this->post(
            '/test',
            ['file' => $file],
        );

        $this->assertResponseStatus(401);
    }

    public function testUploadToOtherClient(): void
    {
        $client = new Client('heseya');
        $client->save();

        $client = new Client('test');
        $client->save();

        $file = UploadedFile::fake()->image('test.jpg');

        $this->post(
            '/heseya',
            ['file' => $file],
            ['x-api-key' => $client->key],
        );

        $this->assertResponseStatus(401);
    }
}
