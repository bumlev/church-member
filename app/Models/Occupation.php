<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occupation extends Model
{
    public $timestamps = false;

    protected $table = 'occupation';

    protected $fillable = ['name'];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

