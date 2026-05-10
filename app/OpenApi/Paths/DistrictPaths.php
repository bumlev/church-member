<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for GET /{province}/districts
 * This class exists solely to hold OpenAPI annotations.
 * It is never instantiated at runtime.
 */
#[OA\PathItem(path: '/{province}/districts')]
#[OA\Get(
    path: '/{province}/districts',
    description: 'Returns all districts belonging to the specified province, ordered alphabetically.',
    summary: 'List districts by province',
    tags: ['Districts'],
    parameters: [
        new OA\Parameter(
            name: 'province',
            description: 'The ID of the province',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'A list of districts for the given province',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/DistrictResource')
                    ),
                ]
            )
        ),
        new OA\Response(response: 404, description: 'Province not found'),
    ]
)]
class DistrictPaths {}

