<?php

namespace App\Http\Controllers;

use App\Models\Hdd;
use Illuminate\Http\Request;

class HddController extends Controller
{
    public function __construct(private Hdd $model = new Hdd())
    {
        parent::__construct($model);
    }
}
