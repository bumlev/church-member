<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    public $timestamps = false;

    protected $table = 'member';

    protected $fillable = [
        'first_name',
        'last_name',
        'sex_id',
        'marital_status_id',
        'fathers_name',
        'mothers_name',
        'occupation_id',
        'employed',
        'education_id',
        'option_id',
        'talent_id',
        'mobile_tel',
        'email',
        'fax_number',
        'province_id',
        'district_id',
        'sector_id',
        'cellule_id',
        'village_id',
        'church_responsibility_id',
    ];

    protected $casts = [
        'employed' => 'boolean',
    ];

    // ── Lookup relationships ────────────────────────────────────────────────

    public function sex(): BelongsTo
    {
        return $this->belongsTo(Sex::class);
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }

    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class);
    }

    public function optionDepartment(): BelongsTo
    {
        return $this->belongsTo(OptionDepartment::class, 'option_id');
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function churchResponsibility(): BelongsTo
    {
        return $this->belongsTo(ChurchResponsibility::class);
    }

    // ── Geographic relationships ────────────────────────────────────────────

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function cellule(): BelongsTo
    {
        return $this->belongsTo(Cellule::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}

