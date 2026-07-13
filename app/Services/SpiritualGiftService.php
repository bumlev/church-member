<?php

namespace App\Services;

use App\Models\SpiritualGift;
use Illuminate\Database\Eloquent\Collection;

class SpiritualGiftService
{
    /**
     * Return all spiritual gifts ordered alphabetically.
     * Used to populate the spiritual gift multi-select on the member registration form.
     */
    public static function getAll(): Collection
    {
        return SpiritualGift::orderBy('name')->get();
    }
}
