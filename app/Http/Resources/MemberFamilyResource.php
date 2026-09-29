<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $family_name
 * @property mixed $address
 * @property mixed $date_formed
 * @property \App\Models\FamilyMembership $pivot
 */
#[OA\Schema(
    schema: 'MemberFamilyResource',
    title: 'MemberFamily',
    description: 'A family the member belongs to, together with the role they play in it',
    properties: [
        new OA\Property(property: 'family_id',   type: 'integer', example: 1),
        new OA\Property(property: 'family_name', type: 'string',  example: 'The Doe Family'),
        new OA\Property(property: 'address',     type: 'string',  example: 'KG 11 Ave, Kigali', nullable: true),
        new OA\Property(property: 'date_formed', type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
        new OA\Property(property: 'role_type',   type: 'string',  example: 'father', enum: ['father', 'mother', 'child', 'guardian']),
        new OA\Property(property: 'start_date',  type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
        new OA\Property(property: 'end_date',    type: 'string',  format: 'date', example: null,         nullable: true),
    ]
)]
class MemberFamilyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'family_id'   => $this->id,
            'family_name' => $this->family_name,
            'address'     => $this->address,
            'date_formed' => $this->date_formed?->toDateString(),
            'role_type'   => $this->pivot->role_type->value,
            'start_date'  => $this->pivot->start_date?->toDateString(),
            'end_date'    => $this->pivot->end_date?->toDateString(),
        ];
    }
}
