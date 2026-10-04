<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for GET /churches
 * This class exists solely to hold OpenAPI annotations.
 * It is never instantiated at runtime.
 */
#[OA\PathItem(path: '/churches')]
#[OA\Get(
    path: '/churches',
    description: 'Returns a collection of all churches ordered alphabetically.',
    summary: 'List all churches',
    security: [['sanctum' => []]],
    tags: ['Churches'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'A list of churches',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/ChurchResource')
                    ),
                ]
            )
        ),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
class ChurchPaths {}
