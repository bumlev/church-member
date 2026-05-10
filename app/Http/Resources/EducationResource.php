<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EducationResource',
    title: 'Education',
    description: 'An education level option for the member registration form',
    properties: [
        new OA\Property(property: 'id',   type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string',  example: 'BACHELOR'),
    ]
)]
class EducationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}

