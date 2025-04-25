<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;

class LocationController extends Controller
{
    public function getProvinces($region)
    {
        $provinces = Province::where('region_id', $region)->pluck('name', 'id');
        return response()->json($provinces);
    }

    public function getCities($province)
    {
        $cities = City::where('province_id', $province)->pluck('name', 'id');
        return response()->json($cities);
    }

    public function getBarangays($city)
    {
        $barangays = Barangay::where('city_id', $city)->pluck('name', 'id');
        return response()->json($barangays);
    }
}
