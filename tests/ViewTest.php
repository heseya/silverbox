<?php

class ViewTest extends TestCase
{
    public function testViewPublicFile(): void
    {
        $this->get('/test/' . $this->file->name);

        $this->assertResponseOk();
    }

    public function testCantViewPrivateFile(): void
    {
        $this->get('/test/' . $this->filePrivate->name);

        $this->assertResponseStatus(401);
    }

    public function testViewPrivateFile(): void
    {
        $this->get('/test/' . $this->filePrivate->name, ['x-api-key' => $this->client->key]);

        $this->assertResponseOk();
    }

    public function testCantViewUserKey(): void
    {
        $this->get('/test/.key');

        $this->assertResponseStatus(401);
    }
}
