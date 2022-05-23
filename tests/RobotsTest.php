<?php

class RobotsTest extends TestCase
{
    public function testRobots(): void
    {
        $this
            ->send('GET', 'robots.txt')
            ->assertOk()
            ->assertSeeText('User-agent: *')
            ->assertSeeText('disallow: /');
    }
}
