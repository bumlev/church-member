<?php

namespace App\Services;

use App\Enums\RoleType;
use App\Models\Family;
use App\Models\FamilyMembership;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class FamilyService
{
    private const int DEFAULT_PER_PAGE = 20;

    private const int MAX_PER_PAGE = 100;

    private const array FAMILY_RELATIONS = ['members'];

    private const array SINGLE_ACTIVE_ROLES = [RoleType::FATHER, RoleType::MOTHER];

    public static function getAllFamilies(array $filters = []): LengthAwarePaginator
    {
        $perPage = min((int) ($filters['per_page'] ?? self::DEFAULT_PER_PAGE), self::MAX_PER_PAGE);

        return Family::with(self::FAMILY_RELATIONS)
                        ->orderBy('family_name')
                        ->paginate($perPage)
                        ->withQueryString();
    }

    public static function getFamilyById(int $id): Family
    {
        return Family::with(self::FAMILY_RELATIONS)
                        ->findOrFail($id);
    }

    public static function createFamily(array $data): Family
    {
        $memberEntries = $data['members'] ?? [];
        unset($data['members']);

        self::assertMemberEntriesAreValid($memberEntries);

        $family = Family::create($data);

        foreach ($memberEntries as $entry) {
            self::addMember($family, $entry);
        }

        return $family->fresh(self::FAMILY_RELATIONS);
    }

    public static function updateFamily(Family $family, array $data): Family
    {
        $memberEntries = $data['members'] ?? null;
        unset($data['members']);

        $family->update($data);

        if ($memberEntries !== null) {
            $family->memberships()->delete();
            foreach ($memberEntries as $entry) {
                self::addMember($family, $entry);
            }
        }

        return $family->fresh(self::FAMILY_RELATIONS);
    }

    public static function addMember(Family $family, array $entry): FamilyMembership
    {
        $roleType = $entry['role_type'] instanceof RoleType
            ? $entry['role_type']
            : RoleType::from($entry['role_type']);

        $memberId = (int) $entry['member_id'];
        $endDate  = $entry['end_date'] ?? null;

        self::assertMemberNotInFamily($family, $memberId);

        if ($endDate === null) {
            if ($roleType !== RoleType::CHILD) {
                self::assertRoleAvailable($family, $roleType);
            }

            if (in_array($roleType, self::SINGLE_ACTIVE_ROLES, true)) {
                self::assertNoConflictingParentRole($memberId, $roleType, $family->id);
            } else {
                self::assertNoConflictingRoleInOtherFamily($memberId, $roleType, $family->id);
            }
        }

        return $family->memberships()->create([
            'member_id'  => $memberId,
            'role_type'  => $roleType,
            'start_date' => $entry['start_date'] ?? null,
            'end_date'   => $endDate,
        ]);
    }

    public static function removeMember(Family $family, int $memberId, ?RoleType $roleType = null): void
    {
        $query = $family->memberships()->where('member_id', $memberId);

        if ($roleType !== null) {
            $query->where('role_type', $roleType);
        }

        $query->delete();
    }

    /**
     * A member can only be recorded once within a given family, regardless of
     * role — a member can't be both "child" and "guardian" in the same family.
     */
    private static function assertMemberNotInFamily(Family $family, int $memberId): void
    {
        $exists = $family->memberships()
            ->where('member_id', $memberId)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'members' => ["Member #{$memberId} is already recorded in this family."],
            ]);
        }
    }

    /**
     * A family may have at most one active (end_date null) member per role,
     * except "child" which allows any number of active members. Adding a
     * second active father/mother/guardian raises a validation error.
     */
    private static function assertRoleAvailable(Family $family, RoleType $roleType): void
    {
        $exists = $family->memberships()
            ->where('role_type', $roleType)
            ->whereNull('end_date')
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'members' => ["This family already has an active {$roleType->value}."],
            ]);
        }
    }

    /**
     * A member can be an active father/mother in at most one family at a time
     * — being father in family A and father (or mother) in family B is just
     * as invalid as father in A and mother in B. A child/guardian role in
     * another family is unaffected, since only father/mother are exclusive.
     *
     * $excludeFamilyId is omitted when validating entries for a family that
     * doesn't exist yet (nothing to exclude); pass the family's id once it's
     * persisted so its own (freshly recreated) memberships aren't flagged.
     */
    private static function assertNoConflictingParentRole(int $memberId, RoleType $roleType, ?int $excludeFamilyId = null): void
    {
        if (! in_array($roleType, self::SINGLE_ACTIVE_ROLES, true)) {
            return;
        }

        $query = FamilyMembership::where('member_id', $memberId)
            ->whereIn('role_type', self::SINGLE_ACTIVE_ROLES)
            ->whereNull('end_date');

        if ($excludeFamilyId !== null) {
            $query->where('family_id', '!=', $excludeFamilyId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'members' => ["This member already has an active father or mother role in another family."],
            ]);
        }
    }

    /**
     * A member can hold a given role (child, guardian, etc.) actively in only
     * one family at a time. Father/mother are handled separately by
     * assertNoConflictingParentRole(), which is stricter (any parent role
     * blocks any other parent role, not just an exact match).
     */
    private static function assertNoConflictingRoleInOtherFamily(int $memberId, RoleType $roleType, ?int $excludeFamilyId = null): void
    {
        $query = FamilyMembership::where('member_id', $memberId)
            ->where('role_type', $roleType)
            ->whereNull('end_date');

        if ($excludeFamilyId !== null) {
            $query->where('family_id', '!=', $excludeFamilyId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'members' => ["This member already has an active {$roleType->value} role in another family."],
            ]);
        }
    }

    /**
     * Validates every member entry before the family row itself is created,
     * so an invalid entry never leaves behind an orphaned family. Checks the
     * batch against itself (duplicate member, more than one active member per
     * role other than "child") and against existing data (member already
     * active in the same role, or as father/mother, elsewhere).
     */
    private static function assertMemberEntriesAreValid(array $memberEntries): void
    {
        $seenMembers      = [];
        $activeRoleCounts = [];

        foreach ($memberEntries as $entry) {
            $roleType = $entry['role_type'] instanceof RoleType
                ? $entry['role_type']
                : RoleType::from($entry['role_type']);

            $memberId = (int) $entry['member_id'];
            $endDate  = $entry['end_date'] ?? null;

            if (isset($seenMembers[$memberId])) {
                throw ValidationException::withMessages([
                    'members' => ["Member #{$memberId} is listed more than once in this family."],
                ]);
            }
            $seenMembers[$memberId] = true;

            if ($endDate !== null) {
                continue;
            }

            if ($roleType === RoleType::CHILD) {
                self::assertNoConflictingRoleInOtherFamily($memberId, $roleType);
                continue;
            }

            $activeRoleCounts[$roleType->value] = ($activeRoleCounts[$roleType->value] ?? 0) + 1;

            if ($activeRoleCounts[$roleType->value] > 1) {
                throw ValidationException::withMessages([
                    'members' => ["A family can only have one active {$roleType->value}."],
                ]);
            }

            if (in_array($roleType, self::SINGLE_ACTIVE_ROLES, true)) {
                self::assertNoConflictingParentRole($memberId, $roleType);
            } else {
                self::assertNoConflictingRoleInOtherFamily($memberId, $roleType);
            }
        }
    }
}
