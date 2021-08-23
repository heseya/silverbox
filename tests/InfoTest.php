<?php

class InfoTest extends TestCase
{
    public function testCantViewUnauthorized(): void
    {
        $this->json('GET', '/test/' . $this->file->name . '/info');

        $this->assertResponseStatus(401);
    }

    public function testViewInfo(): void
    {
        $this->json('GET', '/test/' . $this->file->name . '/info', [], ['x-api-key' => $this->client->key]);

        $this->assertResponseOk();
    }

    public function testCantViewUserKey(): void
    {
        $this->json('GET', '/test/.key/info');

        $this->assertResponseStatus(401);
    }
}
