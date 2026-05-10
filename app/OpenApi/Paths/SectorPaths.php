<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for GET /{district}/sectors
 * This class exists solely to hold OpenAPI annotations.
 * It is never instantiated at runtime.
 */
#[OA\PathItem(path: '/{district}/sectors')]
#[OA\Get(
    path: '/{district}/sectors',
    description: 'Returns all sectors belonging to the specified district, ordered alphabetically.',
    summary: 'List sectors by district',
    tags: ['Sectors'],
    parameters: [
        new OA\Parameter(
            name: 'district',
            description: 'The ID of the district',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'A list of sectors for the given district',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/SectorResource')
                    ),
                ]
            )
        ),
        new OA\Response(response: 404, description: 'District not found'),
    ]
)]
class SectorPaths {}

