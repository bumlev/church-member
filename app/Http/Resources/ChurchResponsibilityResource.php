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
    schema: 'ChurchResponsibilityResource',
    title: 'ChurchResponsibility',
    description: 'A church responsibility option, scoped to a department, for the member registration form',
    properties: [
        new OA\Property(property: 'id',   type: 'integer', example: 4),
        new OA\Property(property: 'name', type: 'string',  example: 'Choir Leader'),
    ]
)]
class ChurchResponsibilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
