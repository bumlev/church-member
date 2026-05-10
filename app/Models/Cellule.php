<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cellule extends Model
{
    public $timestamps = false;

    protected $table = 'cellule';

    protected $fillable = ['sector_id', 'name'];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

