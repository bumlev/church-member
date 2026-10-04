<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $location
 */
#[OA\Schema(
    schema: 'ChurchResource',
    title: 'Church',
    properties: [
        new OA\Property(property: 'id',       type: 'integer', example: 1),
        new OA\Property(property: 'name',     type: 'string',  example: 'Zion Temple'),
        new OA\Property(property: 'location', type: 'string',  example: 'Kigali', nullable: true),
    ]
)]
class ChurchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'location' => $this->location,
        ];
    }
}
