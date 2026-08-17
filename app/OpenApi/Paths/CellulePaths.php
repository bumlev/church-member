<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for GET /{sector}/cellules
 * This class exists solely to hold OpenAPI annotations.
 * It is never instantiated at runtime.
 */
#[OA\PathItem(path: '/{sector}/cellules')]
#[OA\Get(
    path: '/{sector}/cellules',
    description: 'Returns all cellules belonging to the specified sector, ordered alphabetically.',
    summary: 'List cellules by sector',
    security: [['sanctum' => []]],
    tags: ['Cellules'],
    parameters: [
        new OA\Parameter(
            name: 'sector',
            description: 'The ID of the sector',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'A list of cellules for the given sector',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/CelluleResource')
                    ),
                ]
            )
        ),
        new OA\Response(response: 401, description: 'Unauthenticated'),
        new OA\Response(response: 404, description: 'Sector not found'),
    ]
)]
class CellulePaths {}

