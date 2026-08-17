<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static orderBy(string $string)
 */
class Education extends Model
{
    public $timestamps = false;

    protected $table = 'education';

    protected $fillable = ['name'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_education_faculty')->distinct();
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'education_faculty');
    }
}

