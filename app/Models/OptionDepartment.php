<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionDepartment extends Model
{
    public $timestamps = false;

    protected $table = 'option_department';

    protected $fillable = ['name'];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'option_id');
    }
}

