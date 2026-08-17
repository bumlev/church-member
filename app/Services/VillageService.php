<?php

namespace App\Services;

use App\Models\Cellule;
use App\Models\Village;
use Illuminate\Database\Eloquent\Collection;

class VillageService
{
    /**
     * Retrieve all villages belonging to a given cellule, ordered by name.
     */
    public static function getByCellule(Cellule $cellule): Collection
    {
        return Village::where('cellule_id', $cellule->id)
            ->orderBy('name')
            ->get();
    }
}

