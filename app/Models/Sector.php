<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    public $timestamps = false;

    protected $table = 'sector';

    protected $fillable = ['district_id', 'name'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function cellules(): HasMany
    {
        return $this->hasMany(Cellule::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

