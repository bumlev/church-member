<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for authentication endpoints.
 * Each method documents exactly one endpoint (one PathItem + one operation).
 * This class is never instantiated at runtime.
 */
class AuthPaths
{
    // ── Log in ────────────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/login')]
    #[OA\Post(
        path: '/login',
        description: 'Authenticates a user with their email and password and returns a bearer token to use for subsequent authenticated requests.',
        summary: 'Log in',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                        new OA\Property(property: 'token', type: 'string', example: '1|aB3xR9pQmZ...'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid credentials or validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The provided credentials are incorrect.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['email' => ['The provided credentials are incorrect.']]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 429, description: 'Too many login attempts'),
        ]
    )]
    public function login(): void {}

    // ── Log out ───────────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/logout')]
    #[OA\Post(
        path: '/logout',
        description: 'Revokes the bearer token used to authenticate the current request.',
        summary: 'Log out',
        security: [['sanctum' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 204, description: 'Logout successful'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(): void {}

    // ── Request a password reset ─────────────────────────────────────────────
    #[OA\PathItem(path: '/password/forgot')]
    #[OA\Post(
        path: '/password/forgot',
        description: 'Sends a password reset token by email if an account exists for the given address. Always responds with a generic message so callers can\'t use it to discover which emails are registered.',
        summary: 'Request a password reset',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Request processed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'If an account with that email exists, a password reset link has been sent.'),
                    ]
                )
            ),
            new OA\Response(response: 429, description: 'Too many reset attempts'),
        ]
    )]
    public function forgot(): void {}

    // ── Reset a password ─────────────────────────────────────────────────────
    #[OA\PathItem(path: '/password/reset')]
    #[OA\Post(
        path: '/password/reset',
        description: 'Resets a user\'s password using the token they received by email. Revokes all of the user\'s existing bearer tokens.',
        summary: 'Reset a password',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['token', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'token', type: 'string', example: '9d8e2f...'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'newSecret123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'newSecret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password reset successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Your password has been reset successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid/expired token, unknown user, or validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This password reset token is invalid.'),
                    ]
                )
            ),
        ]
    )]
    public function reset(): void {}
}
