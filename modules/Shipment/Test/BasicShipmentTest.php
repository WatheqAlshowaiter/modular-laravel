<?php
namespace Modules\Shipment\Test;

use Tests\TestCase;

class BasicShipmentTest extends TestCase
{
    public function test_basic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
