<?php

namespace Tests\Unit;

use App\Models\Product;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic test for the product model
     */
    public function testProductCrud(): void
    {
        $data = [
            "model"     => 'new model',
            "currency"  => '$',
            "price"     => 987.65,
            "storage"   => 4000,
            "ram_id"    => rand(1, 3),
            "hdd_id"    => rand(1, 3),
            "location_id"   => rand(1, 3),
        ];

        // create
        $prod = Product::create($data);
        $this->assertDatabaseHas('products', $data);

        // modify
        $data['model'] = 'modified';
        $data['price'] = 1000.2;
        $prod->update($data);
        $this->assertDatabaseHas('products', $data);

        // delete
        $prod->delete();
        $this->assertDatabaseMissing('products', $data);
    }
}
