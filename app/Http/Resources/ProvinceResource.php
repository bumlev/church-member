<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProvinceResource',
    title: 'Province',
    properties: [
        new OA\Property(property: 'id',   type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string',  example: 'KIGALI CITY'),
    ]
)]
class ProvinceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
