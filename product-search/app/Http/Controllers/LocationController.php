<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private Location $model = new Location())
    {
        parent::__construct($model);
    }
}
