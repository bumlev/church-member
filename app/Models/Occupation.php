<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static orderBy(string $string)
 */
class Occupation extends Model
{
    public $timestamps = false;

    protected $table = 'occupation';

    protected $fillable = ['name'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_occupation')->distinct();
    }
}

