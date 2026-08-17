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
 */
#[OA\Schema(
    schema: 'FamilyResource',
    title: 'Family',
    description: 'A family unit grouping members by the role they play within it',
    properties: [
        new OA\Property(property: 'id',          type: 'integer', example: 1),
        new OA\Property(property: 'family_name', type: 'string',  example: 'The Doe Family'),
        new OA\Property(property: 'address',     type: 'string',  example: 'KG 11 Ave, Kigali', nullable: true),
        new OA\Property(property: 'date_formed', type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
        new OA\Property(
            property: 'members',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/FamilyMemberResource')
        ),
    ]
)]
class FamilyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'family_name' => $this->family_name,
            'address'     => $this->address,
            'date_formed' => $this->date_formed?->toDateString(),
            'members'     => FamilyMemberResource::collection($this->whenLoaded('members')),
        ];
    }
}
