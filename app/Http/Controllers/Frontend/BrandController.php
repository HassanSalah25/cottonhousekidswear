<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\BrandCollection;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        return new BrandCollection(Brand::all());
    }
}
