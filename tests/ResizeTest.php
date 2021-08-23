<?php

class ResizeTest extends TestCase
{
    public function testViewFile(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['w' => 100]);

        $this->assertResponseOk();
        $this->assertTrue(file_get_contents(__DIR__ . '/files/image.jpeg') !== $response->getContent());
    }

    public function testViewFileSvg(): void
    {
        $response = $this->send('GET', '/test/' . $this->fileSvg->name, ['w' => 100]);

        $this->assertResponseOk();
        $this->assertTrue(file_get_contents(__DIR__ . '/files/image.svg') === $response->getContent());
    }

    public function testNotFound(): void
    {
        $this->send('GET', '/test/not-found-file.jpeg', ['w' => 100]);

        $this->assertResponseStatus(404);
    }
}
