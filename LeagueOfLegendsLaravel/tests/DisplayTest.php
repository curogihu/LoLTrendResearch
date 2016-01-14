<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisplayTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLinkCheck()
    {
        //$this->assertTrue(true);
      $response = $this->visit('/');
      $this->assertEquals(200, $response->status());
    }
}


