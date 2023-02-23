<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                "id"            => 1,
                "model"         => 'ABC 2500 XS',
                "price"         => 5432.99,
                "storage"       => 100,
                "ram_id"        => 1,
                "hdd_id"        => 2,
                "location_id"   => 3,
            ],
            [
                "id"            => 2,
                "model"         => 'XYZ SuperHot 12',
                "price"         => 2345.99,
                "storage"       => 200,
                "ram_id"        => 3,
                "hdd_id"        => 2,
                "location_id"   => 1,
            ],
            [
                "id"            => 3,
                "model"         => 'Precision500 Ultra',
                "price"         => 1234.99,
                "storage"       => 300,
                "ram_id"        => 2,
                "hdd_id"        => 3,
                "location_id"   => 4,
            ],
        ]);
    }
}
