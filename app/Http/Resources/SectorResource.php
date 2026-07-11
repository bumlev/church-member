<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $district_id
 */
#[OA\Schema(
    schema: 'SectorResource',
    title: 'Sector',
    properties: [
        new OA\Property(property: 'id',          type: 'integer', example: 1),
        new OA\Property(property: 'name',        type: 'string',  example: 'KIMIRONKO'),
        new OA\Property(property: 'district_id', type: 'integer', example: 1),
    ]
)]
class SectorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'district_id' => $this->district_id,
        ];
    }
}
