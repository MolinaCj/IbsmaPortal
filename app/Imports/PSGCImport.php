<?php

namespace App\Imports;


use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PSGCImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if the row contains region data
        if (!empty($row['region_code']) && empty($row['province_code']) && empty($row['city_municipality_code']) && empty($row['barangay_code'])) {
            return new Region([
                'id'   => $row['region_code'],
                'name' => $row['region_name'],
            ]);
        }

        // Check if the row contains province data
        if (!empty($row['province_code']) && empty($row['city_municipality_code']) && empty($row['barangay_code'])) {
            return new Province([
                'id'        => $row['province_code'],
                'name'      => $row['province_name'],
                'region_id' => $row['region_code'],
            ]);
        }

        // Check if the row contains city/municipality data
        if (!empty($row['city_municipality_code']) && empty($row['barangay_code'])) {
            return new City([
                'id'          => $row['city_municipality_code'],
                'name'        => $row['city_municipality_name'],
                'province_id' => $row['province_code'],
            ]);
        }

        // Check if the row contains barangay data
        if (!empty($row['barangay_code'])) {
            return new Barangay([
                'id'        => $row['barangay_code'],
                'name'      => $row['barangay_name'],
                'city_id'   => $row['city_municipality_code'],
            ]);
        }

        return null; // Ignore invalid rows
    }
}
