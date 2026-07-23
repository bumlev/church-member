<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    private const array MEMBER_RELATIONS = [
        'sex', 'educations', 'faculties', 'departments', 'churchResponsibilities', 'talents', 'occupations', 'spiritualGifts',
    ];

    private const string PICTURE_DISK = 'public';

    private const string PICTURE_DIRECTORY = 'members/pictures';

    public static function getAllMembers(): Collection
    {
        return Member::with(self::MEMBER_RELATIONS)
                        ->orderBy('last_name')
                        ->orderBy('first_name')
                        ->get();
    }

    public static function getMemberById(String $id): Member
    {
        return Member::with(self::MEMBER_RELATIONS)
                        ->findOrFail($id);
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
