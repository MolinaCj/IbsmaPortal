<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AddressService
{
    protected $baseUrl = 'https://psgc.gitlab.io/api/';

    public function getRegions()
    {
        return Http::get($this->baseUrl . 'regions.json')->json();
    }

    public function getProvinces($regionCode)
    {
        return Http::get($this->baseUrl . "regions/{$regionCode}/provinces.json")->json();
    }

    public function getCitiesMunicipalities($provinceCode)
    {
        return Http::get($this->baseUrl . "provinces/{$provinceCode}/cities-municipalities.json")->json();
    }

    public function getBarangays($cityOrMunicipalityCode)
    {
        return Http::get($this->baseUrl . "cities-municipalities/{$cityOrMunicipalityCode}/barangays.json")->json();
    }
}
