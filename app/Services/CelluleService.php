<?php

namespace App\Services;

use App\Models\Cellule;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Collection;

class CelluleService
{
    /**
     * Retrieve all cellules belonging to a given sector, ordered by name.
     */
    public static function getBySector(Sector $sector): Collection
    {
        return Cellule::where('sector_id', $sector->id)
            ->orderBy('name')
            ->get();
    }
}

