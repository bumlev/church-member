<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for Family endpoints.
 * Each method documents exactly one endpoint (one PathItem + one operation).
 * This class is never instantiated at runtime.
 */
class FamilyPaths
{
    // ── List all families ────────────────────────────────────────────────────
    #[OA\PathItem(path: '/families')]
    #[OA\Get(
        path: '/families',
        description: 'Returns a paginated collection of all families ordered by family name, together with their members.',
        summary: 'List all families',
        security: [['sanctum' => []]],
        tags: ['Families'],
        parameters: [
            new OA\Parameter(name: 'page', description: 'Page number to retrieve.', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, default: 1, example: 1)),
            new OA\Parameter(name: 'per_page', description: 'Number of families per page (max 100).', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, default: 20, example: 20)),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 200,
                description: 'A paginated list of families',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/FamilyResource')
                        ),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
        ]
    )]
    public function index(): void {}

    // ── Create a family ──────────────────────────────────────────────────────
    #[OA\Post(
        path: '/families',
        description: 'Creates a new family, optionally assigning members and the role each plays (father, mother, child, guardian). A family may have at most one active father and one active mother at a time.',
        summary: 'Create a new family',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['family_name'],
                properties: [
                    new OA\Property(property: 'family_name', type: 'string', example: 'The Doe Family', maxLength: 150),
                    new OA\Property(property: 'address',     type: 'string', example: 'KG 11 Ave, Kigali', nullable: true, maxLength: 255),
                    new OA\Property(property: 'date_formed', type: 'string', format: 'date', example: '2010-06-12', nullable: true),
                    new OA\Property(
                        property: 'members',
                        description: 'Members to assign to this family, each paired with the role they play',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'member_id',  type: 'integer', example: 1),
                                new OA\Property(property: 'role_type',  type: 'string',  example: 'father', enum: ['father', 'mother', 'child', 'guardian']),
                                new OA\Property(property: 'start_date', type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
                                new OA\Property(property: 'end_date',   type: 'string',  format: 'date', example: null,         nullable: true),
                            ],
                            type: 'object'
                        ),
                        nullable: true
                    ),
                ]
            )
        ),
        tags: ['Families'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 201,
                description: 'Family created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/FamilyResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The family name field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['family_name' => ['The family name field is required.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function store(): void {}

    // ── Get single family ─────────────────────────────────────────────────────
    #[OA\PathItem(path: '/families/{id}')]
    #[OA\Get(
        path: '/families/{id}',
        description: 'Returns the details of a specific family by its ID, including its members and their roles.',
        summary: 'Get a single family',
        security: [['sanctum' => []]],
        tags: ['Families'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the family',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 200,
                description: 'Family found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/FamilyResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Family not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Family] 99'),
                    ]
                )
            ),
        ]
    )]
    public function show(): void {}

    // ── Update a family ───────────────────────────────────────────────────────
    #[OA\Put(
        path: '/families/{id}',
        description: 'Updates a family record. All fields are optional. Passing `members` replaces the existing member/role assignments entirely.',
        summary: 'Update an existing family',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'family_name', type: 'string', example: 'The Doe Family', nullable: true, maxLength: 150),
                    new OA\Property(property: 'address',     type: 'string', example: 'KG 11 Ave, Kigali', nullable: true, maxLength: 255),
                    new OA\Property(property: 'date_formed', type: 'string', format: 'date', example: '2010-06-12', nullable: true),
                    new OA\Property(
                        property: 'members',
                        description: 'Replaces the members assigned to this family, each paired with the role they play',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'member_id',  type: 'integer', example: 1),
                                new OA\Property(property: 'role_type',  type: 'string',  example: 'father', enum: ['father', 'mother', 'child', 'guardian']),
                                new OA\Property(property: 'start_date', type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
                                new OA\Property(property: 'end_date',   type: 'string',  format: 'date', example: null,         nullable: true),
                            ],
                            type: 'object'
                        ),
                        nullable: true
                    ),
                ]
            )
        ),
        tags: ['Families'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the family to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 200,
                description: 'Family updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/FamilyResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Family not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Family] 99'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This family already has an active father.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['members' => ['This family already has an active father.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function update(): void {}

    // ── Delete a family ───────────────────────────────────────────────────────
    #[OA\Delete(
        path: '/families/{id}',
        description: 'Deletes a family record. Membership rows for this family are removed via cascading delete.',
        summary: 'Delete a family',
        security: [['sanctum' => []]],
        tags: ['Families'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the family to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 204, description: 'Family deleted successfully'),
            new OA\Response(
                response: 404,
                description: 'Family not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Family] 99'),
                    ]
                )
            ),
        ]
    )]
    public function destroy(): void {}

    // ── Add a member to a family ─────────────────────────────────────────────
    #[OA\PathItem(path: '/families/{family}/members')]
    #[OA\Post(
        path: '/families/{family}/members',
        description: 'Assigns a member to a family with a given role. Rejects a second active (no end_date) father or mother for the same family.',
        summary: 'Add a member to a family',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['member_id', 'role_type'],
                properties: [
                    new OA\Property(property: 'member_id',  type: 'integer', example: 1),
                    new OA\Property(property: 'role_type',  type: 'string',  example: 'child', enum: ['father', 'mother', 'child', 'guardian']),
                    new OA\Property(property: 'start_date', type: 'string',  format: 'date', example: '2010-06-12', nullable: true),
                    new OA\Property(property: 'end_date',   type: 'string',  format: 'date', example: null,         nullable: true),
                ]
            )
        ),
        tags: ['Families'],
        parameters: [
            new OA\Parameter(
                name: 'family',
                description: 'The ID of the family',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 201,
                description: 'Member added to the family',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/FamilyResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This family already has an active father.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['members' => ['This family already has an active father.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function addMember(): void {}

    // ── Remove a member from a family ────────────────────────────────────────
    #[OA\PathItem(path: '/families/{family}/members/{member}')]
    #[OA\Delete(
        path: '/families/{family}/members/{member}',
        description: 'Removes all role assignments a member holds within the given family.',
        summary: 'Remove a member from a family',
        security: [['sanctum' => []]],
        tags: ['Families'],
        parameters: [
            new OA\Parameter(
                name: 'family',
                description: 'The ID of the family',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'member',
                description: 'The ID of the member to remove',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 204, description: 'Member removed from the family'),
        ]
    )]
    public function removeMember(): void {}
}
