<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $role
 * @property mixed $must_change_password
 * @property mixed $created_at
 */
#[OA\Schema(
    schema: 'UserResource',
    title: 'User',
    description: 'A user account of the application',
    properties: [
        new OA\Property(property: 'id',                   type: 'integer', example: 1),
        new OA\Property(property: 'name',                  type: 'string',  example: 'Jane Doe'),
        new OA\Property(property: 'email',                 type: 'string',  format: 'email', example: 'jane.doe@example.com'),
        new OA\Property(property: 'role',                  type: 'string',  example: 'user', enum: ['user', 'admin']),
        new OA\Property(property: 'must_change_password',  type: 'boolean', example: true),
        new OA\Property(property: 'created_at',            type: 'string',  format: 'date-time', example: '2026-01-15T10:30:00+00:00', nullable: true),
    ]
)]
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'role'                  => $this->role,
            'must_change_password'  => $this->must_change_password,
            'created_at'            => $this->created_at?->toIso8601String(),
        ];
    }
}
