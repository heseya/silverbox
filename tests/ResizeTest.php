<?php

class ResizeTest extends TestCase
{
    public function testViewFile(): void
    {
        $this->call('GET', '/test/' . $this->file->name, ['w' => 100]);

        $this->assertResponseOk();
    }

    public function testNotFound(): void
    {
        $this->call('GET', '/test/not-found-file.jpeg', ['w' => 100]);

        $this->assertResponseStatus(404);
    }
}
