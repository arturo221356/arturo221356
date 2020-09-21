<?php

namespace Tests\Feature;

use App\Imei;
use App\Sucursal;
use Database\Factories\EquipoFactory;
use Database\Factories\IccFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        

        $response = Sucursal::factory()->times(10)->create();
        dump($response);
    }
}
