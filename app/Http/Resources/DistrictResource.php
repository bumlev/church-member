<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $province_id
 */
#[OA\Schema(
    schema: 'DistrictResource',
    title: 'District',
    properties: [
        new OA\Property(property: 'id',          type: 'integer', example: 1),
        new OA\Property(property: 'name',        type: 'string',  example: 'GASABO'),
        new OA\Property(property: 'province_id', type: 'integer', example: 1),
    ]
)]
class DistrictResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'province_id' => $this->province_id,
        ];
    }
}
