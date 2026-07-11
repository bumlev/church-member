<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static orderBy(string $string)
 */
class Department extends Model
{
    public $timestamps = false;

    protected $table = 'department';

    protected $fillable = ['name'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_department_church_responsibility')->distinct();
    }

    public function church_responsibilities(): HasMany
    {
        return $this->hasMany(ChurchResponsibility::class);
    }
}

