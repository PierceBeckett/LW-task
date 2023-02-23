<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hdd extends Model
{
    use HasFactory;

    protected $table = 'hdd';

    protected $fillable = [
        'value',
    ];
}
