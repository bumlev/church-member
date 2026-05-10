<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;

class ProvinceService
{
    /**
     * Retrieve all provinces ordered by name.
     */
    public static function getAll(): Collection
    {
        return Province::orderBy('name')->get();
    }
}

