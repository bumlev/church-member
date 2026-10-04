<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static orderBy(string $string)
 * @property mixed $id
 * @property mixed $name
 * @property mixed $location
 * @property mixed $members
 */
class Church extends Model
{
    public $timestamps = false;

    protected $table = 'churches';

    protected $fillable = ['name', 'location'];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
