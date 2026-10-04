<?php

namespace App\Services;

use App\Models\Church;
use Illuminate\Database\Eloquent\Collection;

class ChurchService
{
    /**
     * Retrieve all churches ordered by name.
     */
    public static function getAll(): Collection
    {
        return Church::orderBy('name')->get();
    }
}
