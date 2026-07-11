<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $cellule_id
 */
#[OA\Schema(
    schema: 'VillageResource',
    title: 'Village',
    properties: [
        new OA\Property(property: 'id',         type: 'integer', example: 1),
        new OA\Property(property: 'name',       type: 'string',  example: 'AKABAHIZI'),
        new OA\Property(property: 'cellule_id', type: 'integer', example: 1),
    ]
)]
class VillageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'cellule_id' => $this->cellule_id,
        ];
    }
}
