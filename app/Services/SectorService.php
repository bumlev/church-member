<?php

namespace App\Services;

use App\Models\District;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Collection;

class SectorService
{
    /**
     * Retrieve all sectors belonging to a given district, ordered by name.
     */
    public static function getByDistrict(District $district): Collection
    {
        return Sector::where('district_id', $district->id)
            ->orderBy('name')
            ->get();
    }
}

