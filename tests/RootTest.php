<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RootTest extends TestCase
{
    /**
     * A basic test.
     *
     * @return void
     */
    public function testRoot()
    {
        $this->get('/');

        $this->assertEquals(
            200, $this->response->status()
        );
    }
}
