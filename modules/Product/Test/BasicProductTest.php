<?php

use Tests\TestCase;

class BasicProductTest extends TestCase
{
    public function test_basic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
