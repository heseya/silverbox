<?php

use App\Client;
use Illuminate\Http\UploadedFile;

class UploadTest extends TestCase
{
    public UploadedFile $uploadedFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadedFile = UploadedFile::fake()->image('test.jpg');
    }

    public function testUploadPublicFile(): void
    {
        $this->json(
            'POST',
            '/test',
            ['file' => $this->uploadedFile],
            ['x-api-key' => $this->client->key],
        );

        $this->assertResponseOk();
    }

    public function testUploadPrivateFile(): void
    {
        $this->json(
            'POST',
            '/test?private',
            ['file' => $this->uploadedFile],
            ['x-api-key' => $this->client->key],
        );

        $this->assertResponseOk();
    }

    public function testUploadUnauthorized(): void
    {
        $this->json(
            'POST',
            '/test',
            ['file' => $this->uploadedFile],
        );

        $this->assertResponseStatus(401);
    }

    public function testUploadToOtherClient(): void
    {
        $otherClient = new Client('heseya');
        $otherClient->save();

        $this->json(
            'POST',
            '/heseya',
            ['file' => $this->uploadedFile],
            ['x-api-key' => $this->client->key],
        );

        $this->assertResponseStatus(401);
    }
}
