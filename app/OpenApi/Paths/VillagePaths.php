<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for GET /{cellule}/villages
 * This class exists solely to hold OpenAPI annotations.
 * It is never instantiated at runtime.
 */
#[OA\PathItem(path: '/{cellule}/villages')]
#[OA\Get(
    path: '/{cellule}/villages',
    description: 'Returns all villages belonging to the specified cellule, ordered alphabetically.',
    summary: 'List villages by cellule',
    security: [['sanctum' => []]],
    tags: ['Villages'],
    parameters: [
        new OA\Parameter(
            name: 'cellule',
            description: 'The ID of the cellule',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'A list of villages for the given cellule',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/VillageResource')
                    ),
                ]
            )
        ),
        new OA\Response(response: 401, description: 'Unauthenticated'),
        new OA\Response(response: 404, description: 'Cellule not found'),
    ]
)]
class VillagePaths {}

