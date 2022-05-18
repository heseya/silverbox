<?php

class ConvertAndResizeTest extends TestCase
{
    public function testViewFile(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'webp', 'w' => 100]);

        $this->assertResponseOk();

        $returnedFileInfo = getimagesizefromstring($response->getContent());
        $originalFileInfo = getimagesize(__DIR__ . '/files/image.jpeg');

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/webp', $response->headers->get('Content-Type'));
        $this->assertEquals('image/webp', $returnedFileInfo['mime']);
        $this->assertNotEquals($returnedFileInfo[0], $originalFileInfo[0]);
        $this->assertEquals(100, $returnedFileInfo[0]);
    }

    public function testViewFileSvg(): void
    {
        $response = $this->send('GET', '/test/' . $this->fileSvg->name, ['format' => 'avif', 'w' => 100]);

        $this->assertResponseOk();
        $this->assertEquals(file_get_contents(__DIR__ . '/files/image.svg'), $response->getContent());
    }
}
