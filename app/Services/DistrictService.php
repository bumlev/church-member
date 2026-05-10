<?php

namespace App\Services;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;

class DistrictService
{
    /**
     * Retrieve all districts belonging to a given province, ordered by name.
     */
    public static function getByProvince(Province $province): Collection
    {
        return District::where('province_id', $province->id)
            ->orderBy('name')
            ->get();
    }
}

