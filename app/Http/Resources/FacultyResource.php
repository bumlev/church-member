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
    schema: 'FacultyResource',
    title: 'Faculty',
    description: 'A faculty option, scoped to an education level, for the member registration form',
    properties: [
        new OA\Property(property: 'id',   type: 'integer', example: 4),
        new OA\Property(property: 'name', type: 'string',  example: 'Faculty of Engineering'),
    ]
)]
class FacultyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
