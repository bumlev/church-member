<?php

namespace App\Services;

use App\Models\Education;
use Illuminate\Database\Eloquent\Collection;

class FacultyService
{
    /**
     * Return all faculties available for the given education level, ordered alphabetically.
     * Used to populate the faculty multi-select on the member registration form.
     */
    public static function getByEducation(Education $education): Collection
    {
        return $education->faculties()->orderBy('name')->get();
    }
}
