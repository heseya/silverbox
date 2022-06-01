<?php

use App\Exceptions\Handler;
use Illuminate\Http\Request;

class ErrorHandleTest extends TestCase
{
    protected Request $request;
    protected Throwable $throwable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(Request::class);
        $this->throwable = $this->createMock(Throwable::class);
    }

    public function testAppDebugEnabled(): void
    {
        putenv('APP_DEBUG=true');
        $this->assertEquals(true, env('APP_DEBUG'));
        $handler = new Handler();
        $error = $handler->render($this->request, $this->throwable);

        $this->assertObjectHasAttribute('file', $error->getData());
        $this->assertObjectHasAttribute('line', $error->getData());
        $this->assertObjectHasAttribute('trace', $error->getData());
    }

    /*public function testAppDebugDisabled(): void
    {
        putenv('APP_DEBUG=false');
        $this->assertEquals(false, env('APP_DEBUG'));
        $handler = new Handler();
        $error = $handler->render($this->request, $this->throwable);

        $this->assertObjectNotHasAttribute('file', $error->getData());
        $this->assertObjectNotHasAttribute('line', $error->getData());
        $this->assertObjectNotHasAttribute('trace', $error->getData());
    }*/
}
