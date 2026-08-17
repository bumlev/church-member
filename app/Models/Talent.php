<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static orderBy(string $string)
 */
class Talent extends Model
{
    public $timestamps = false;

    protected $table = 'talent';

    protected $fillable = ['name'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_talent')->distinct();
    }
}
