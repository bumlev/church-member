<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $sector_id
 */
#[OA\Schema(
    schema: 'CelluleResource',
    title: 'Cellule',
    properties: [
        new OA\Property(property: 'id',        type: 'integer', example: 1),
        new OA\Property(property: 'name',      type: 'string',  example: 'BIBARE'),
        new OA\Property(property: 'sector_id', type: 'integer', example: 1),
    ]
)]
class CelluleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'sector_id' => $this->sector_id,
        ];
    }
}
