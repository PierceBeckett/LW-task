<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testAppRuns(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testProductsList() : void
    {
        $response = $this->get('api/product');
        $response
            ->assertStatus(200)
            ->assertJsonIsArray();

        // 3 seeder products
        $this->assertCount(3, json_decode($response->content()));
    }

    public function testProductAddDelete() : void
    {
        $data = [
            "model"         => 'model ABC new',
            "currency"      => '£',
            "price"         => 999.50,
            "storage"       => 6000,
            "ram_id"        => rand(1, 3),
            "hdd_id"        => rand(1, 3),
            "location_id"   => rand(1, 3),
        ];
        $response = $this->post('api/product', $data);
        print 'x'.$response->content().'x';
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', $data);

        $new_id = json_decode($response->content())->id;

        $response = $this->delete('api/product/'.$new_id);
        $response->assertStatus(200);
        $this->assertEquals(true, $response->content());
        $this->assertDatabaseMissing('products', $data);
    }

    public function testRamList() : void
    {
        $response = $this->get('api/ram');
        $response
            ->assertStatus(200)
            ->assertJsonIsArray();
    }
    public function testHddList() : void
    {
        $response = $this->get('api/hdd');
        $response
            ->assertStatus(200)
            ->assertJsonIsArray();
    }
    public function testLocationList() : void
    {
        $response = $this->get('api/location');
        $response
            ->assertStatus(200)
            ->assertJsonIsArray();
    }
}
