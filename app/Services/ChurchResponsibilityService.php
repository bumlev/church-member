<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class ChurchResponsibilityService
{
    /**
     * Return all church responsibilities available for the given department, ordered alphabetically.
     * Used to populate the church responsibility multi-select on the member registration form.
     */
    public static function getByDepartment(Department $department): Collection
    {
        return $department->church_responsibilities()->orderBy('name')->get();
    }
}
