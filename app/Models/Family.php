<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail(int $id)
 * @method static orderBy(string $string)
 * @method static create(array $data)
 * @property mixed $id
 * @property mixed $family_name
 * @property mixed $address
 * @property mixed $date_formed
 */
class Family extends Model
{
    public $timestamps = false;

    protected $table = 'family';

    protected $fillable = ['family_name', 'address', 'date_formed'];

    protected $casts = [
        'date_formed' => 'date',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(FamilyMembership::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'family_membership')
            ->using(FamilyMembership::class)
            ->withPivot('id', 'role_type', 'start_date', 'end_date');
    }
}
