<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    /**
     * Return all departments ordered alphabetically.
     * Used to populate the department dropdown on the member registration form.
     */
    public static function getAll(): Collection
    {
        return Department::orderBy('name')->get();
    }
}

