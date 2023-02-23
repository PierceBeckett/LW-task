<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HddSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hdd')->insert([
            [
                "id"    => 1,
                "value" => 'SAS',
            ],
            [
                "id"    => 2,
                "value" => 'SATA',
            ],
            [
                "id"    => 3,
                "value" => 'SSD',
            ],
        ]);
    }
}
