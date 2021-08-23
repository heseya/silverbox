<?php

use Illuminate\Support\Facades\Storage;

class DeleteTest extends TestCase
{
    public function testCantDeleteUnauthorized(): void
    {
        $this->json('DELETE', '/test/' . $this->file->name);

        $this->assertResponseStatus(401);

        Storage::assertExists($this->file->path());
    }

    public function testDelete(): void
    {
        $this->json('DELETE', '/test/' . $this->file->name, [], ['x-api-key' => $this->client->key]);

        $this->assertResponseStatus(204);

        Storage::assertMissing($this->file->path());
    }
}
