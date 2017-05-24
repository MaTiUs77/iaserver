<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Aoicollector extends TestCase
{
    public function testIndex()
    {
        $this->visit('/aoicollector/inspection');
    }
	
	public function testSearch()
	{
		$this->visit('/aoicollector/inspection/search/0011561996');
	}
	
	public function testMultiplesearch()
	{
		$this->visit('/aoicollector/inspection/search/multiplesearch');
	}
	
	public function testShow()
	{
		$this->visit('/aoicollector/inspection/show/14');
	}
}
