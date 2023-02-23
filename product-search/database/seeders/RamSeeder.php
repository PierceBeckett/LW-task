<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ram')->insert([
            [
                "id"    => 1,
                "value" => '2GB',
            ],
            [
                "id"    => 2,
                "value" => '4GB',
            ],
            [
                "id"    => 3,
                "value" => '8GB',
            ],
            [
                "id"    => 4,
                "value" => '12GB',
            ],
            [
                "id"    => 5,
                "value" => '16GB',
            ],
        ]);
    }
}
