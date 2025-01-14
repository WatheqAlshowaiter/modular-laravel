<?php


use Tests\TestCase;

class BasicShipmentTest extends TestCase
{
    public function testBasic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
