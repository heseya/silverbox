<?php

class UpdateTest extends TestCase
{
    public function testCantUpdate(): void
    {
        $this->send('PATCH', '/test/' . $this->file->name);

        $this->assertResponseStatus(401);
    }

    public function testCantUpdateWithoutSlug(): void
    {
        $this->json('PATCH', '/test/' . $this->file->name, [], ['x-api-key' => $this->client->key]);

        $this->assertResponseStatus(422);
    }

    public function testUpdate(): void
    {
        $this->json(
            'PATCH',
            '/test/' . $this->file->name,
            ['slug' => 'test'],
            ['x-api-key' => $this->client->key],
        );
        $this->assertResponseOk();

        // check if file exists
        $this->send('GET', '/test/test.jpg');
        $this->assertResponseOk();

        // check if you can't create same slug twice
        $this->json(
            'PATCH',
            '/test/' . $this->filePrivate->name,
            ['slug' => 'test'],
            ['x-api-key' => $this->client->key],
        );
        $this->assertResponseStatus(422);
    }
}
