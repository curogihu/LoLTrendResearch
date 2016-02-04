<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisplayOnTopPageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
      $response = $this->call('GET', '/');
      $this->assertRegexp('/<title>LoL Trend Research<\/title>/', $response->getContent());
    }
}
