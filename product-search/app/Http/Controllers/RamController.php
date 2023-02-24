<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use Illuminate\Http\Request;

class RamController extends Controller
{
    public function __construct(private Ram $model = new Ram())
    {
        $this->sort_by = "CAST(value AS DECIMAL)";
        parent::__construct($model);
    }
}
