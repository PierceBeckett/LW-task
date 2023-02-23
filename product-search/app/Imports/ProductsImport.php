<?php

namespace App\Imports;

use App\Models\Hdd;
use App\Models\Location;
use App\Models\Product;
use App\Models\Ram;
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
    {print 'x';
        // sort out the lookup values
        if (!Ram::where('value', $row['ram'])->exists()) {
            Ram::create(['value' => $row['ram']]);
        }
        if (!Hdd::where('value', $row['hdd'])->exists()) {
            Hdd::create(['value' => $row['hdd']]);
        }
        if (!Location::where('value', $row['location'])->exists()) {
            Location::create(['value' => $row['location']]);
        }
print 'y';
        // make the product
        return new Product([
            "model"         => $row['model'],
            "ram_id"        => Ram::where('value', $row['ram'])->first()->id,
            "hdd_id"        => Hdd::where('value', $row['hdd'])->first()->id,
            "location_id"   => Location::where('value', $row['location'])->first()->id,
            "currency"      => substr($row['price'], 0, 1),
            "price"         => substr($row['price'], 1),
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
}
