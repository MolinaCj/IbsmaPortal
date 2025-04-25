<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // Get regions
    public function getRegions()
    {
        $regions = $this->addressService->getRegions();
        return response()->json($regions);
    }

    // Get provinces by region code
    public function getProvinces($regionCode)
    {
        $provinces = $this->addressService->getProvinces($regionCode);
        return response()->json($provinces);
    }

    public function getRegionCitiesMunicipalities($regionCode)
    {
        $response = Http::get("https://psgc.gitlab.io/api/regions/{$regionCode}/cities-municipalities.json");
        return response()->json($response->json());
    }

    // Get cities and municipalities by province code
    public function getCitiesMunicipalities($provinceCode)
    {
        $cities = $this->addressService->getCitiesMunicipalities($provinceCode);
        return response()->json($cities);
    }

    // Get barangays by city or municipality code
    public function getBarangays($cityOrMunicipalityCode)
    {
        $barangays = $this->addressService->getBarangays($cityOrMunicipalityCode);
        return response()->json($barangays);
    }
}
