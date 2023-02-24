<?php

namespace App\Imports;

use App\Models\Hdd;
use App\Models\Location;
use App\Models\Product;
use App\Models\Ram;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \App\Models\Product|null
    */
    public function model(array $row) : ?Product
    {
        // sort out the lookup values
        $ramval = $this->tidyRam($row['ram']);
        $hddval = $this->hddType($row['hdd']);
        if (!Ram::where('value', $ramval)->exists()) {
            Ram::create(['value' => $ramval]);
        }
        if (!Hdd::where('value', $hddval)->exists()) {
            Hdd::create(['value' => $hddval]);
        }
        if (!Location::where('value', $row['location'])->exists()) {
            Location::create(['value' => $row['location']]);
        }

        // make the product
        return new Product([
            "model"         => $row['model'],
            "ram_id"        => Ram::where('value', $ramval)->first()->id,
            "hdd_id"        => Hdd::where('value', $hddval)->first()->id,
            "location_id"   => Location::where('value', $row['location'])->first()->id,
            "currency"      => preg_replace('/[0-9.]+/', '', $row['price']),
            "price"         => floatval(preg_replace('/[^0-9.]+/', '', $row['price'])),
            "storage"       => $this->calcStorage($row['hdd']),
        ]);
    }

    /**
     * Takes a string like 2x8GBSATA2
     * dividing the first counter (2x8) up into two parts and multiplying them
     * them multiplies that by 1000 if its in TB rather than GB
     *
     * @param string $hdd
     * @return int
     */
    private function calcStorage(string $hdd) : int
    {
        $multiplier = str_contains($hdd, 'GB') ? 1 : 1000;
        $base_amount = intval(substr($hdd, 0, strpos('x', $hdd)+1)) *
            intval(substr($hdd, strpos('x', $hdd)+2));
        return  $base_amount * $multiplier;
    }

    /**
     * Converts the ram info to the numeric amount with Bytesize
     *
     * @param string $ram
     * @return string
     */
    private function tidyRam(string $ram) : string
    {
        return substr($ram, 0, strpos($ram, 'GB') + 2);
    }

    /**
     * Decides the Type from Hdd info
     *
     * @param string $hdd
     * @return string
     */
    private function hddType(string $hdd) : string
    {
        if (str_contains($hdd, 'SAS')) {
            return 'SAS';
        }
        if (str_contains($hdd, 'SATA')) {
            return 'SATA';
        }
        if (str_contains($hdd, 'SSD')) {
            return 'SSD';
        }
        
        return 'unknown';
    }
}
