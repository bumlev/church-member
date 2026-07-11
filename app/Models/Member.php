<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static findOrFail(int $id)
 * @method static orderBy(string $string)
 * @method static create(array $data)
 * @property mixed $id
 */
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
        'employed',
        'mobile_tel',
        'email',
        'fax_number',
        'province_id',
        'district_id',
        'sector_id',
        'cellule_id',
        'village_id'
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

    public function occupations(): BelongsToMany
    {
        return $this->belongsToMany(Occupation::class, 'member_occupation')->distinct();
    }

    public function talents(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class, 'member_talent')->distinct();
    }

    public function educations(): BelongsToMany
    {
        return $this->belongsToMany(Education::class, 'member_education_faculty')->distinct();
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'member_education_faculty')
            ->withPivot('education_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'member_department_church_responsibility')->distinct();
    }

    public function churchResponsibilities(): BelongsToMany
    {
        return $this->belongsToMany(ChurchResponsibility::class, 'member_department_church_responsibility')
            ->withPivot('department_id');
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

