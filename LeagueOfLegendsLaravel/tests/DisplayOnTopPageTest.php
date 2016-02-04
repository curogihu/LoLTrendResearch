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

      $this->checkTitle($response);
      $this->checkItems($response);
    }


    private function checkTitle($response)
    {
      $this->assertRegexp('/<title>LoL Trend Research<\/title>/', $response->getContent());
    }

    private function checkItems($response)
    {
      $this->assertRegexp('/<a href="\/whenbuy" class="menuItem">When buy<\/a>/', $response->getContent());
      $this->assertRegexp('/<a href="\/whenkilled" class="menuItem">When killed<\/a>/', $response->getContent());
      $this->assertRegexp('/<a href="\/wherelane" class="menuItem">Where lane<\/a>/', $response->getContent());
      $this->assertRegexp('/<a href="\/howmanycs" class="menuItem">How many CS<\/a>/', $response->getContent());
      $this->assertRegexp('/<a href="\/form" class="menuItem">Search<\/a>/', $response->getContent());
    }
}
