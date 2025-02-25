<?php

class ViewTest extends TestCase
{
    public function testViewPublicFile(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name);

        $this->assertResponseOk();
        $this->assertTrue(file_get_contents(__DIR__ . '/files/image.jpeg') === $response->getContent());
    }

    public function testViewPublicFileSvg(): void
    {
        $this->send('GET', '/test/' . $this->fileSvg->name);

        $this->assertResponseOk();
    }

    public function testNotFound(): void
    {
        $this->send('GET', '/test/not-found-file.jpeg');

        $this->assertResponseStatus(404);
    }

    public function testCantViewPrivateFile(): void
    {
        $this->send('GET', '/test/' . $this->filePrivate->name);

        $this->assertResponseStatus(401);
    }

    public function testViewPrivateFile(): void
    {
        $this->send('GET', '/test/' . $this->filePrivate->name, [], ['x-api-key' => $this->client->key]);

        $this->assertResponseOk();
    }

    public function testCantViewUserKey(): void
    {
        $this->send('GET', '/test/.key');

        $this->assertResponseStatus(401);
    }

    public function testVideoAsPartialContent(): void
    {
        $response = $this->send('GET', '/test/' . $this->fileVideo->name, [], ['Range' => 'bytes=0-999']);
        ob_start();
        $this->assertEquals(file_get_contents(__DIR__ . '/files/video.mp4'), $response->streamedContent());

        $this->assertResponseStatus(206);
        ob_flush();
    }
}
