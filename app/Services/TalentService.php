<?php

namespace App\Services;

use App\Models\Talent;
use Illuminate\Database\Eloquent\Collection;

class TalentService
{
    /**
     * Return all talents ordered alphabetically.
     * Used to populate the talent multi-select on the member registration form.
     */
    public static function getAll(): Collection
    {
        return Talent::orderBy('name')->get();
    }
}
