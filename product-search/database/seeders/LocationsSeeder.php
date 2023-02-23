<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                "id"    => 1,
                "value" => 'AmsterdamAMS-01',
            ],
            [
                "id"    => 2,
                "value" => 'Washington D.C.WDC-01',
            ],
            [
                "id"    => 3,
                "value" => 'SingaporeSIN-11',
            ],
            [
                "id"    => 4,
                "value" => 'DallasDAL-10',
            ],
        ]);
    }
}
