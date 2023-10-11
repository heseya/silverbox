<?php

class ConvertTest extends TestCase
{
    public function testViewFileWebp(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'webp']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/webp', $response->headers->get('Content-Type'));
        $this->assertEquals('image/webp', $returnedFileInfo['mime']);
    }

    /*public function testViewFileAvif(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'avif']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/avif', $response->headers->get('Content-Type'));
        $this->assertEquals('image/avif', $returnedFileInfo['mime']);
    }*/

    public function testViewFilePng(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'png']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/png', $response->headers->get('Content-Type'));
        $this->assertEquals('image/png', $returnedFileInfo['mime']);
    }

    public function testViewFileJpeg(): void
    {
        $response = $this->send('GET', '/test/' . $this->filePng->name, ['format' => 'jpeg']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/jpeg', $response->headers->get('Content-Type'));
        $this->assertEquals('image/jpeg', $returnedFileInfo['mime']);
    }

    public function testNotSupportedFormat(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'exe']);

        $this->assertResponseOk();
        $this->assertEquals(mime_content_type(__DIR__ . '/files/image.jpeg'), $response->headers->get('Content-Type'));
    }

    public function testNotFound(): void
    {
        $this->send('GET', '/test/not-found-file.jpeg', ['format' => 'webp']);

        $this->assertResponseStatus(404);
    }

    public function testViewFileAutoJpeg(): void
    {
        $response = $this->send('GET', '/test/' . $this->filePng->name, ['format' => 'auto'], ['Accept' => 'image/jpeg']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/jpeg', $response->headers->get('Content-Type'));
        $this->assertEquals('image/jpeg', $returnedFileInfo['mime']);
    }

    public function testViewFileAutoWebp(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'auto'], ['Accept' => 'image/jpeg,image/png,image/webp']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertNotEquals(file_get_contents(__DIR__ . '/files/image.jpeg'), $response->getContent());
        $this->assertEquals('image/webp', $response->headers->get('Content-Type'));
        $this->assertEquals('image/webp', $returnedFileInfo['mime']);
    }

    public function testViewFileAutoNotSupported(): void
    {
        $response = $this->send('GET', '/test/' . $this->file->name, ['format' => 'auto'], ['Accept' => 'image/gif']);

        $this->assertResponseOk();
        $returnedFileInfo = getimagesizefromstring($response->getContent());

        $this->assertEquals('image/jpeg', $response->headers->get('Content-Type'));
        $this->assertEquals('image/jpeg', $returnedFileInfo['mime']);
    }
}
