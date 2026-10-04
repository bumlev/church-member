<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail(int $id)
 * @method static orderBy(string $string)
 * @method static create(array $data)
 * @method static where(string $string, mixed $first_name)
 * @property mixed $id
 * @property mixed $national_id
 * @property mixed $picture
 * @property bool $picture_on_remote
 * @property-read int|null $age
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $sex
 * @property mixed $maritalStatus
 * @property mixed $date_birthday
 * @property mixed $email
 * @property mixed $mobile_tel
 * @property mixed $employed
 * @property bool $is_member
 * @property bool $attends_sunday_school
 * @property mixed $fathers_name
 * @property mixed $mothers_name
 * @property mixed $date_salvation
 * @property mixed $date_baptism
 * @property mixed $member_since
 * @property mixed $province
 * @property mixed $district
 * @property mixed $sector
 * @property mixed $cellule
 * @property mixed $cell
 * @property mixed $village
 * @property mixed $church
 * @property mixed $occupations
 * @property mixed $educations
 * @property mixed $faculties
 * @property mixed $departments
 * @property mixed $churchResponsibilities
 * @property mixed $talents
 * @property mixed $spiritualGifts
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
        'is_member',
        'attends_sunday_school',
        'mobile_tel',
        'email',
        'national_id',
        'picture',
        'picture_on_remote',
        'date_birthday',
        'date_salvation',
        'date_baptism',
        'member_since',
        'province_id',
        'district_id',
        'sector_id',
        'cellule_id',
        'cell_id',
        'village_id',
        'church_id',
    ];

    protected $casts = [
        'employed'          => 'boolean',
        'is_member'             => 'boolean',
        'attends_sunday_school' => 'boolean',
        'picture_on_remote'     => 'boolean',
        'date_birthday'     => 'date',
        'date_salvation'    => 'date',
        'date_baptism'      => 'date',
        'member_since'      => 'date',
    ];

    protected $appends = [
        'age',
    ];

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date_birthday ? (int) $this->date_birthday->diffInYears(now()) : null,
        );
    }

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

    public function spiritualGifts(): BelongsToMany
    {
        return $this->belongsToMany(SpiritualGift::class, 'member_spiritual_gift')->distinct();
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

    // ── Family relationships ────────────────────────────────────────────────

    public function familyMemberships(): HasMany
    {
        return $this->hasMany(FamilyMembership::class);
    }

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_membership')
            ->using(FamilyMembership::class)
            ->withPivot('id', 'role_type', 'start_date', 'end_date');
    }

    // ── Church relationship ─────────────────────────────────────────────────

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
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

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}

