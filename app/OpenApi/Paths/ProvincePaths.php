<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for GET /provinces
 * This class exists solely to hold OpenAPI annotations.
 * It is never instantiated at runtime.
 */
#[OA\PathItem(path: '/provinces')]
#[OA\Get(
    path: '/provinces',
    description: 'Returns a collection of all provinces ordered alphabetically.',
    summary: 'List all provinces',
    tags: ['Provinces'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'A list of provinces',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/ProvinceResource')
                    ),
                ]
            )
        ),
    ]
)]
class ProvincePaths {}

