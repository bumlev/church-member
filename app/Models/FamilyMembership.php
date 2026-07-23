<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property mixed $id
 * @property mixed $family_id
 * @property mixed $member_id
 * @property RoleType $role_type
 * @property mixed $start_date
 * @property mixed $end_date
 */
class FamilyMembership extends Pivot
{
    public $incrementing = true;

    public $timestamps = false;

    protected $table = 'family_membership';

    protected $fillable = ['family_id', 'member_id', 'role_type', 'start_date', 'end_date'];

    protected $casts = [
        'role_type'  => RoleType::class,
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
