<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    public $timestamps = false;

    protected $table = 'village';

    protected $fillable = ['cellule_id', 'name'];

    public function cellule(): BelongsTo
    {
        return $this->belongsTo(Cellule::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

