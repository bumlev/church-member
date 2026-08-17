<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 */
#[OA\Schema(
    schema: 'OccupationResource',
    title: 'Occupation',
    description: 'An occupation option for the member registration form',
    properties: [
        new OA\Property(property: 'id',   type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string',  example: 'TEACHER'),
    ]
)]
class OccupationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}

