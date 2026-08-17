<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 */
class Province extends Model
{
    public $timestamps = false;

    protected $table = 'province';

    protected $fillable = ['name'];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

