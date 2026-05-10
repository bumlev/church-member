<?php

namespace App\Services;

use App\Models\Occupation;
use Illuminate\Database\Eloquent\Collection;

class OccupationService
{
    /**
     * Return all occupations ordered alphabetically.
     * Used to populate the occupation dropdown on the member registration form.
     */
    public static function getAll(): Collection
    {
        return Occupation::orderBy('name')->get();
    }
}

