<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

/**
 * Shared schema components describing Laravel's default paginated-resource
 * JSON shape (`links` + `meta`), reused by every paginated list endpoint.
 * This class is never instantiated at runtime.
 */
#[OA\Schema(
    schema: 'PaginationLinks',
    title: 'Pagination Links',
    properties: [
        new OA\Property(property: 'first', type: 'string', format: 'uri', example: 'http://localhost/api/v1/members?page=1'),
        new OA\Property(property: 'last', type: 'string', format: 'uri', example: 'http://localhost/api/v1/members?page=5'),
        new OA\Property(property: 'prev', type: 'string', format: 'uri', example: null, nullable: true),
        new OA\Property(property: 'next', type: 'string', format: 'uri', example: 'http://localhost/api/v1/members?page=2', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'PaginationMeta',
    title: 'Pagination Meta',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(property: 'from', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'last_page', type: 'integer', example: 5),
        new OA\Property(
            property: 'links',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'url', type: 'string', format: 'uri', example: null, nullable: true),
                    new OA\Property(property: 'label', type: 'string', example: '1'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ],
                type: 'object'
            )
        ),
        new OA\Property(property: 'path', type: 'string', format: 'uri', example: 'http://localhost/api/v1/members'),
        new OA\Property(property: 'per_page', type: 'integer', example: 20),
        new OA\Property(property: 'to', type: 'integer', example: 20, nullable: true),
        new OA\Property(property: 'total', type: 'integer', example: 93),
    ]
)]
class PaginationSchemas
{
}
