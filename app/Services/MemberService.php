<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    private const int DEFAULT_PER_PAGE = 20;

    private const int MAX_PER_PAGE = 100;

    private const array MEMBER_RELATIONS = [
        'sex', 'maritalStatus' , 'educations', 'faculties', 'departments', 'churchResponsibilities', 'talents', 'occupations', 'spiritualGifts',
    ];

    private const string PICTURE_DISK = 'public';

    private const string PICTURE_DIRECTORY = 'members/pictures';

    /** Fields matched with `LIKE %value%` rather than an exact value. */
    private const array PARTIAL_MATCH_FIELDS = ['first_name', 'last_name', 'fathers_name', 'mothers_name'];

    /** FK fields filtered with `whereIn`, accepting a single ID or a list. */
    private const array EXACT_LIST_FIELDS = [
        'sex_id', 'marital_status_id',
        'province_id', 'district_id', 'sector_id', 'cellule_id', 'cell_id', 'village_id',
    ];

    /** Date columns exposed as `{column}_from` / `{column}_to` range filters. */
    private const array DATE_RANGE_COLUMNS = ['date_birthday', 'date_salvation', 'date_baptism', 'member_since'];

    /** Many-to-many relations filtered by "belongs to any of these IDs". */
    private const array RELATION_ID_FILTERS = [
        'occupation_id'             => 'occupations',
        'talent_id'                 => 'talents',
        'spiritual_gift_id'         => 'spiritualGifts',
        'education_id'              => 'educations',
        'faculty_id'                => 'faculties',
        'department_id'             => 'departments',
        'church_responsibility_id'  => 'churchResponsibilities',
    ];

    public static function filterMembers(array $filters): LengthAwarePaginator
    {
        $query = Member::with(self::MEMBER_RELATIONS);

        self::applyPartialMatchFilters($query, $filters);
        self::applyExactListFilters($query, $filters);
        self::applyExactFilter($query, $filters, 'national_id');
        self::applyExactFilter($query, $filters, 'employed');
        self::applyAgeFilter($query, $filters);
        self::applyDateRangeFilters($query, $filters);
        self::applyRelationIdFilters($query, $filters);
        self::applyFamilyFilters($query, $filters);

        $perPage = min((int) ($filters['per_page'] ?? self::DEFAULT_PER_PAGE), self::MAX_PER_PAGE);

        return $query->orderBy('last_name')
                        ->orderBy('first_name')
                        ->paginate($perPage)
                        ->withQueryString();
    }

    public static function getMemberById(String $id): Member
    {
        return Member::with(self::MEMBER_RELATIONS)
                        ->findOrFail($id);
    }

    private static function applyPartialMatchFilters(Builder $query, array $filters): void
    {
        foreach (self::PARTIAL_MATCH_FIELDS as $field) {
            if (filled($filters[$field] ?? null)) {
                $query->where($field, 'like', '%' . $filters[$field] . '%');
            }
        }
    }

    private static function applyExactListFilters(Builder $query, array $filters): void
    {
        foreach (self::EXACT_LIST_FIELDS as $field) {
            if (!empty($filters[$field])) {
                $query->whereIn($field, $filters[$field]);
            }
        }
    }

    private static function applyExactFilter(Builder $query, array $filters, string $field): void
    {
        $value = $filters[$field] ?? null;

        if ($value !== null) {
            $query->where($field, $value);
        }
    }

    /**
     * `age` is a derived accessor, not a DB column, so age_min/age_max are
     * translated into the equivalent whole-years-elapsed comparison against
     * date_birthday — mirroring the accessor's diffInYears semantics.
     */
    private static function applyAgeFilter(Builder $query, array $filters): void
    {
        if (($filters['age_min'] ?? null) !== null) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, date_birthday, CURDATE()) >= ?', [$filters['age_min']]);
        }

        if (($filters['age_max'] ?? null) !== null) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, date_birthday, CURDATE()) <= ?', [$filters['age_max']]);
        }
    }

    private static function applyDateRangeFilters(Builder $query, array $filters): void
    {
        foreach (self::DATE_RANGE_COLUMNS as $column) {
            $from = $filters["{$column}_from"] ?? null;
            $to   = $filters["{$column}_to"] ?? null;

            if ($from !== null) {
                $query->whereDate($column, '>=', $from);
            }

            if ($to !== null) {
                $query->whereDate($column, '<=', $to);
            }
        }
    }

    private static function applyRelationIdFilters(Builder $query, array $filters): void
    {
        foreach (self::RELATION_ID_FILTERS as $field => $relation) {
            $ids = $filters[$field] ?? null;

            if (!empty($ids)) {
                $query->whereHas($relation, fn (Builder $relationQuery) => $relationQuery->whereIn('id', $ids));
            }
        }
    }

    /**
     * `family_id` and `family_role_type` both narrow the same `families`
     * relation, so a member matches only when a single family membership
     * satisfies whichever of the two were given.
     */
    private static function applyFamilyFilters(Builder $query, array $filters): void
    {
        $familyIds = $filters['family_id'] ?? null;
        $roleType  = $filters['family_role_type'] ?? null;

        if (empty($familyIds) && $roleType === null) {
            return;
        }

        $query->whereHas('families', function (Builder $relationQuery) use ($familyIds, $roleType) {
            if (!empty($familyIds)) {
                $relationQuery->whereIn('family.id', $familyIds);
            }

            if ($roleType !== null) {
                $relationQuery->where('family_membership.role_type', $roleType);
            }
        });
    }

    public static function createMember(array $data): Member
    {
        $educationEntries = $data['education'] ?? [];
        unset($data['education']);
        $departmentEntries = $data['department'] ?? [];
        unset($data['department']);
        $talentIds = $data['talent'] ?? [];
        unset($data['talent']);
        $spiritualGiftIds = $data['spiritual_gift'] ?? [];
        unset($data['spiritual_gift']);
        $occupationIds = $data['occupation'] ?? [];
        unset($data['occupation']);

        $data = self::preparePicture($data);

        $member = Member::create($data);
        $member->faculties()->sync(self::pivotFromEducationEntries($educationEntries));
        $member->churchResponsibilities()->sync(self::pivotFromDepartmentEntries($departmentEntries));
        $member->talents()->sync($talentIds);
        $member->spiritualGifts()->sync($spiritualGiftIds);
        $member->occupations()->sync($occupationIds);

        return $member->fresh(self::MEMBER_RELATIONS);
    }

    public static function updateMember(Member $member, array $data): Member
    {
        if (array_key_exists('education', $data)) {
            $member->faculties()->sync(self::pivotFromEducationEntries($data['education']));
        }
        unset($data['education']);

        if (array_key_exists('department', $data)) {
            $member->churchResponsibilities()->sync(self::pivotFromDepartmentEntries($data['department']));
        }
        unset($data['department']);

        if (array_key_exists('talent', $data)) {
            $member->talents()->sync($data['talent']);
        }
        unset($data['talent']);

        if (array_key_exists('spiritual_gift', $data)) {
            $member->spiritualGifts()->sync($data['spiritual_gift']);
        }
        unset($data['spiritual_gift']);

        if (array_key_exists('occupation', $data)) {
            $member->occupations()->sync($data['occupation']);
        }
        unset($data['occupation']);

        $data = self::preparePicture($data, $member);

        $member->update($data);
        return $member->fresh(self::MEMBER_RELATIONS);
    }

    /**
     * Flattens [{education_id, faculty: [...]}] entries into pivot sync data
     * keyed by faculty_id, each carrying its paired education_id.
     */
    private static function pivotFromEducationEntries(array $educationEntries): array
    {
        $pivotData = [];

        foreach ($educationEntries as $entry) {
            foreach ($entry['faculty'] ?? [] as $facultyId) {
                $pivotData[$facultyId] = ['education_id' => $entry['education_id']];
            }
        }

        return $pivotData;
    }

    /**
     * Flattens [{department_id, church_responsibility: [...]}] entries into pivot sync
     * data keyed by church_responsibility_id, each carrying its paired department_id.
     */
    private static function pivotFromDepartmentEntries(array $departmentEntries): array
    {
        $pivotData = [];

        foreach ($departmentEntries as $entry) {
            foreach ($entry['church_responsibility'] ?? [] as $responsibilityId) {
                $pivotData[$responsibilityId] = ['department_id' => $entry['department_id']];
            }
        }

        return $pivotData;
    }

    /**
     * Stores a newly uploaded picture on the public disk and replaces the
     * previous file (if any). When no new file is uploaded, the existing
     * picture path is left untouched.
     */
    private static function preparePicture(array $data, ?Member $member = null): array
    {
        if (!($data['picture'] ?? null) instanceof UploadedFile) {
            unset($data['picture']);

            return $data;
        }

        if ($member?->picture) {
            Storage::disk(self::PICTURE_DISK)->delete($member->picture);
        }

        $data['picture'] = Storage::disk(self::PICTURE_DISK)
            ->putFile(self::PICTURE_DIRECTORY, $data['picture']);

        return $data;
    }
}
