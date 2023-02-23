<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'storage',
        'price',
        'ram_id',
        'hdd_id',
        'location_id',
    ];

    protected $appends = ['ram','hdd','location'];

    public function getRamAttribute() : string
    {
        return Ram::find($this->ram_id)->value;
    }
    public function getHddAttribute() : string
    {
        return Hdd::find($this->hdd_id)->value;
    }
    public function getLocationAttribute() : string
    {
        return Location::find($this->location_id)->value;
    }
}
