<?php


use Tests\TestCase;

class BasicOrderTest extends TestCase
{
    public function testBasic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
