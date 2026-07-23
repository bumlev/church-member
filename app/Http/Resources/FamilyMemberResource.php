<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $first_name
 * @property mixed $last_name
 * @property \App\Models\FamilyMembership $pivot
 */
#[OA\Schema(
    schema: 'FamilyMemberResource',
    title: 'FamilyMember',
    description: 'A member within a family, together with the role they play in it',
    properties: [
        new OA\Property(property: 'member_id',  type: 'integer', example: 1),
        new OA\Property(property: 'first_name', type: 'string',  example: 'John'),
        new OA\Property(property: 'last_name',  type: 'string',  example: 'Doe'),
        new OA\Property(property: 'role_type',  type: 'string',  example: 'father', enum: ['father', 'mother', 'child', 'guardian']),
        new OA\Property(property: 'start_date', type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
        new OA\Property(property: 'end_date',   type: 'string',  format: 'date', example: null,         nullable: true),
    ]
)]
class FamilyMemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'member_id'  => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'role_type'  => $this->pivot->role_type->value,
            'start_date' => $this->pivot->start_date?->toDateString(),
            'end_date'   => $this->pivot->end_date?->toDateString(),
        ];
    }
}
