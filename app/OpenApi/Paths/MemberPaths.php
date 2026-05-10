<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

/**
 * Swagger documentation for Member form-data endpoints.
 * Each method documents exactly one endpoint (one PathItem + one Get).
 * This class is never instantiated at runtime.
 */
class MemberPaths
{
    // ── Occupations ──────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/occupations')]
    #[OA\Get(
        path: '/members/occupations',
        summary: 'List all occupations',
        description: 'Returns all occupations ordered alphabetically. Use this to populate the occupation dropdown on the member registration form.',
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of occupations',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/OccupationResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function occupations(): void {}

    // ── Educations ───────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/educations')]
    #[OA\Get(
        path: '/members/educations',
        summary: 'List all education levels',
        description: 'Returns all education levels ordered alphabetically. Use this to populate the education dropdown on the member registration form.',
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of education levels',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/EducationResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function educations(): void {}

    // ── Departments ──────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/departments')]
    #[OA\Get(
        path: '/members/departments',
        summary: 'List all departments',
        description: 'Returns all departments ordered alphabetically. Use this to populate the department dropdown on the member registration form.',
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of departments',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/DepartmentResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function departments(): void {}
}
