<?php

namespace App\Services;

use App\Models\Education;
use Illuminate\Database\Eloquent\Collection;

class EducationService
{
    /**
     * Return all education levels ordered alphabetically.
     * Used to populate the education dropdown on the member registration form.
     */
    public static function getAll(): Collection
    {
        return Education::orderBy('name')->get();
    }
}

