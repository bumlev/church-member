<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\LazyCollection;

class MemberService
{
    private const int DEFAULT_PER_PAGE = 20;

    private const int MAX_PER_PAGE = 100;

    /** Cap on rows returned by the duplicate-check search — an exact match on all three fields is expected to be rare. */
    private const int DUPLICATE_CHECK_LIMIT = 10;

    private const array MEMBER_RELATIONS = [
        'sex', 'maritalStatus' , 'educations', 'faculties', 'departments', 'churchResponsibilities', 'talents', 'occupations', 'spiritualGifts',
    ];

    /** Extra relations the Excel export needs to print location names instead of IDs. */
    private const array EXPORT_RELATIONS = ['province', 'district', 'sector', 'cellule', 'cell', 'village'];

    private const int EXPORT_CHUNK_SIZE = 500;

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
        $perPage = min((int) ($filters['per_page'] ?? self::DEFAULT_PER_PAGE), self::MAX_PER_PAGE);

        return self::buildFilterQuery($filters)
                        ->paginate($perPage)
                        ->withQueryString();
    }

    /**
     * Same filters as filterMembers() but unpaginated, streamed lazily so a
     * large export does not load every member into memory at once.
     */
    public static function filterMembersForExport(array $filters): LazyCollection
    {
        return self::buildFilterQuery($filters)
                        ->with(self::EXPORT_RELATIONS)
                        ->lazy(self::EXPORT_CHUNK_SIZE);
    }

    private static function buildFilterQuery(array $filters): Builder
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

        return $query->orderBy('last_name')
                        ->orderBy('first_name')
                        ->orderBy('id');
    }

    public static function getMemberById(String $id): Member
    {
        return Member::with(self::MEMBER_RELATIONS)
                        ->findOrFail($id);
    }

    /**
     * Looks up members that exactly match the given identity fields, used to
     * warn staff of a likely-duplicate registration while the form is filled.
     */
    public static function findPotentialDuplicates(array $criteria): Collection
    {
        return Member::where('first_name', $criteria['first_name'])
                        ->where('last_name', $criteria['last_name'])
                        ->where('date_birthday', $criteria['date_birthday'])
                        ->orderBy('id')
                        ->limit(self::DUPLICATE_CHECK_LIMIT)
                        ->get();
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
        $pictureFile = $data['pictureFile'] ?? null;
        unset($data['pictureFile']);

        $member = Member::create($data);
        self::savePicture($member, $pictureFile);
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
        $pictureFile = $data['pictureFile'] ?? null;
        unset($data['pictureFile']);

        $member->update($data);
        self::savePicture($member, $pictureFile);

        return $member->fresh(self::MEMBER_RELATIONS);
    }

    /**
     * Deletes the member row (pivot and family-membership rows go with it via
     * cascading FKs), then removes its picture locally and — via queue — from
     * remote storage. The row is deleted first so a failed delete never leaves
     * a member pointing at a missing file.
     */
    public static function deleteMember(Member $member): void
    {
        $picture = $member->picture;

        $member->delete();

        if ($picture) {
            MemberPictureService::delete($picture);
        }
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
     * Stores the uploaded `pictureFile`, replacing the previous file (if any),
     * saves its path on the member and queues the remote mirror. The new file
     * is not on remote storage yet, so the sync flag is reset. When no file is
     * uploaded, the existing picture is left untouched.
     */
    private static function savePicture(Member $member, mixed $file): void
    {
        if (!$file instanceof UploadedFile) {
            return;
        }

        $member->update([
            'picture'           => MemberPictureService::store($file, $member->picture),
            'picture_on_remote' => false,
        ]);

        MemberPictureService::mirror($member);
    }
}
