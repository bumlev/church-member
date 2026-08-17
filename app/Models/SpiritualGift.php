<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static orderBy(string $string)
 */
class SpiritualGift extends Model
{
    public $timestamps = false;

    protected $table = 'spiritual_gift';

    protected $fillable = ['name'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_spiritual_gift')->distinct();
    }
}
