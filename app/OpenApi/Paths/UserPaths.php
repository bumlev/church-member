<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for Admin user-management endpoints.
 * Each method documents exactly one endpoint (one PathItem + one operation).
 * This class is never instantiated at runtime.
 */
class UserPaths
{
    // ── List all users ───────────────────────────────────────────────────────
    #[OA\PathItem(path: '/admin/users')]
    #[OA\Get(
        path: '/admin/users',
        description: 'Returns a collection of all user accounts ordered by name. Requires an authenticated admin user.',
        summary: 'List all users',
        security: [['sanctum' => []]],
        tags: ['Admin: Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of users',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/UserResource')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'The authenticated user is not an admin'),
        ]
    )]
    public function index(): void {}

    // ── Create a user ────────────────────────────────────────────────────────
    #[OA\Post(
        path: '/admin/users',
        description: 'Creates a new user account with the `user` role and a randomly generated temporary password. The password is returned once in the response and must be changed on first login.',
        summary: 'Create a new user',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email'],
                properties: [
                    new OA\Property(property: 'name',  type: 'string', example: 'Jane Doe', maxLength: 150),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com', maxLength: 150),
                ]
            )
        ),
        tags: ['Admin: Users'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                        new OA\Property(property: 'temporary_password', type: 'string', example: 'aB3xR9pQmZ...', description: 'One-time temporary password. Only ever returned in this response.'),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'The authenticated user is not an admin'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'A user with this email already exists.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['email' => ['A user with this email already exists.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function store(): void {}

    // ── Get single user ───────────────────────────────────────────────────────
    #[OA\PathItem(path: '/admin/users/{user}')]
    #[OA\Get(
        path: '/admin/users/{user}',
        description: 'Returns the details of a specific user account by its ID. Requires an authenticated admin user.',
        summary: 'Get a single user',
        security: [['sanctum' => []]],
        tags: ['Admin: Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'The ID of the user',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'The authenticated user is not an admin'),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 99'),
                    ]
                )
            ),
        ]
    )]
    public function show(): void {}

    // ── Update a user ─────────────────────────────────────────────────────────
    #[OA\Put(
        path: '/admin/users/{user}',
        description: 'Updates a user account. All fields are optional.',
        summary: 'Update an existing user',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name',  type: 'string', example: 'Jane Doe', nullable: true, maxLength: 150),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com', nullable: true, maxLength: 150),
                ]
            )
        ),
        tags: ['Admin: Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'The ID of the user to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'The authenticated user is neither an admin nor the account owner'),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 99'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'A user with this email already exists.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['email' => ['A user with this email already exists.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function update(): void {}

    // ── Delete a user ─────────────────────────────────────────────────────────
    #[OA\Delete(
        path: '/admin/users/{user}',
        description: 'Deletes a user account. Requires an authenticated admin user.',
        summary: 'Delete a user',
        security: [['sanctum' => []]],
        tags: ['Admin: Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'The ID of the user to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 204, description: 'User deleted successfully'),
            new OA\Response(response: 403, description: 'The authenticated user is not an admin'),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 99'),
                    ]
                )
            ),
        ]
    )]
    public function destroy(): void {}

    // ── Update a user's role ─────────────────────────────────────────────────
    #[OA\PathItem(path: '/admin/users/{user}/role')]
    #[OA\Patch(
        path: '/admin/users/{user}/role',
        description: 'Changes a user\'s role. Requires an authenticated admin user.',
        summary: "Update a user's role",
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['role'],
                properties: [
                    new OA\Property(property: 'role', type: 'string', example: 'admin', enum: ['user', 'admin']),
                ]
            )
        ),
        tags: ['Admin: Users'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'The ID of the user whose role is being changed',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Role updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'The authenticated user is not an admin'),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 99'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Role must be one of: user, admin.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['role' => ['Role must be one of: user, admin.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function updateRole(): void {}
}
