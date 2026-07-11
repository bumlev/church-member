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
    // ── List all members ─────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members')]
    #[OA\Get(
        path: '/members',
        description: 'Returns a collection of all church members ordered by last name then first name.',
        summary: 'List all members',
        tags: ['Members'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of church members',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/MemberResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): void {}

    // ── Create a member ──────────────────────────────────────────────────────
    #[OA\Post(
        path: '/members',
        description: 'Creates a new church member record. Returns the created member.',
        summary: 'Create a new member',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'talent', 'sex_id', 'marital_status_id'],
                properties: [
                    new OA\Property(property: 'first_name',        type: 'string',  example: 'John',          maxLength: 100),
                    new OA\Property(property: 'last_name',         type: 'string',  example: 'Doe',           maxLength: 100),
                    new OA\Property(
                        property: 'talent',
                        description: 'IDs of talents selected (at least one required)',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 2]
                    ),
                    new OA\Property(property: 'sex_id',            type: 'integer', example: 1),
                    new OA\Property(property: 'marital_status_id', type: 'integer', example: 1),
                    new OA\Property(property: 'fathers_name',      type: 'string',  example: 'James Doe',     nullable: true),
                    new OA\Property(property: 'mothers_name',      type: 'string',  example: 'Mary Doe',      nullable: true),
                    new OA\Property(property: 'employed',          type: 'boolean', example: true,            nullable: true),
                    new OA\Property(
                        property: 'occupation',
                        description: 'IDs of occupations selected',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [2],
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'education',
                        description: 'Education levels selected, each paired with its own faculty IDs',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'education_id', type: 'integer', example: 3),
                                new OA\Property(
                                    property: 'faculty',
                                    description: 'IDs of faculties (must belong to this entry\'s education_id)',
                                    type: 'array',
                                    items: new OA\Items(type: 'integer'),
                                    example: [4, 5],
                                    nullable: true
                                ),
                            ],
                            type: 'object'
                        ),
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'department',
                        description: 'Departments selected, each paired with its own church responsibility IDs',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'department_id', type: 'integer', example: 1),
                                new OA\Property(
                                    property: 'church_responsibility',
                                    description: 'IDs of church responsibilities (must belong to this entry\'s department_id)',
                                    type: 'array',
                                    items: new OA\Items(type: 'integer'),
                                    example: [1, 2],
                                    nullable: true
                                ),
                            ],
                            type: 'object'
                        ),
                        nullable: true
                    ),
                    new OA\Property(property: 'mobile_tel',        type: 'string',  example: '+250788000000', nullable: true),
                    new OA\Property(property: 'email',             type: 'string',  example: 'john@example.com', nullable: true),
                    new OA\Property(property: 'fax_number',        type: 'string',  example: null,            nullable: true),
                    new OA\Property(property: 'province_id',       type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'district_id',       type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'sector_id',         type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'cellule_id',        type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'village_id',        type: 'integer', example: 1,               nullable: true),
                ]
            )
        ),
        tags: ['Members'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Member created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/MemberResource'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The first name field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['first_name' => ['The first name field is required.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function store(): void {}

    // ── Get single member ────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/{id}')]
    #[OA\Get(
        path: '/members/{id}',
        description: 'Returns the details of a specific church member by their ID.',
        summary: 'Get a single member',
        tags: ['Members'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the member',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Member found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/MemberResource'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Member not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Member] 99'),
                    ]
                )
            ),
        ]
    )]
    public function show(): void {}

    // ── Update a member ──────────────────────────────────────────────────────
    #[OA\Put(
        path: '/members/{id}',
        description: 'Updates a church member record. All fields are optional (PATCH behaviour). Returns the updated member.',
        summary: 'Update an existing member',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'John', nullable: true, maxLength: 100),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe', nullable: true, maxLength: 100),
                    new OA\Property(
                        property: 'talent',
                        description: 'IDs of talents selected (at least one required if provided)',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 2],
                        nullable: true
                    ),
                    new OA\Property(property: 'sex_id',            type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'marital_status_id', type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'fathers_name',      type: 'string',  example: 'James Doe',        nullable: true),
                    new OA\Property(property: 'mothers_name',      type: 'string',  example: 'Mary Doe',         nullable: true),
                    new OA\Property(property: 'employed',          type: 'boolean', example: true,               nullable: true),
                    new OA\Property(
                        property: 'occupation',
                        description: 'IDs of occupations selected',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [2],
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'education',
                        description: 'Education levels selected, each paired with its own faculty IDs',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'education_id', type: 'integer', example: 3),
                                new OA\Property(
                                    property: 'faculty',
                                    description: 'IDs of faculties (must belong to this entry\'s education_id)',
                                    type: 'array',
                                    items: new OA\Items(type: 'integer'),
                                    example: [4, 5],
                                    nullable: true
                                ),
                            ],
                            type: 'object'
                        ),
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'department',
                        description: 'Departments selected, each paired with its own church responsibility IDs',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'department_id', type: 'integer', example: 1),
                                new OA\Property(
                                    property: 'church_responsibility',
                                    description: 'IDs of church responsibilities (must belong to this entry\'s department_id)',
                                    type: 'array',
                                    items: new OA\Items(type: 'integer'),
                                    example: [1, 2],
                                    nullable: true
                                ),
                            ],
                            type: 'object'
                        ),
                        nullable: true
                    ),
                    new OA\Property(property: 'mobile_tel',        type: 'string',  example: '+250788000000',    nullable: true),
                    new OA\Property(property: 'email',             type: 'string',  example: 'john@example.com', nullable: true),
                    new OA\Property(property: 'fax_number',        type: 'string',  example: null,               nullable: true),
                    new OA\Property(property: 'province_id',       type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'district_id',       type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'sector_id',         type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'cellule_id',        type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'village_id',        type: 'integer', example: 1,                  nullable: true),
                ]
            )
        ),
        tags: ['Members'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the member to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Member updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/MemberResource'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Member not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Member] 99'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The email must be a valid email address.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['email' => ['The email must be a valid email address.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function update(): void {}

    // ── Occupations ──────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/occupations')]
    #[OA\Get(
        path: '/members/occupations',
        description: 'Returns all occupations ordered alphabetically. Use this to populate the occupation multi-select on the member registration form.',
        summary: 'List all occupations',
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

    // ── Talents ──────────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/talents')]
    #[OA\Get(
        path: '/members/talents',
        description: 'Returns all talents ordered alphabetically. Use this to populate the talent multi-select on the member registration form.',
        summary: 'List all talents',
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of talents',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/TalentResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function talents(): void {}

    // ── Educations ───────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/educations')]
    #[OA\Get(
        path: '/members/educations',
        description: 'Returns all education levels ordered alphabetically. Use this to populate the education dropdown on the member registration form.',
        summary: 'List all education levels',
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

    // ── Faculties (scoped to an education level) ────────────────────────────
    #[OA\PathItem(path: '/members/educations/{education}/faculties')]
    #[OA\Get(
        path: '/members/educations/{education}/faculties',
        description: 'Returns all faculties available for the given education level, ordered alphabetically. Use this to populate the faculty multi-select on the member registration form once an education level is chosen.',
        summary: 'List faculties for an education level',
        tags: ['Member Form Data'],
        parameters: [
            new OA\Parameter(
                name: 'education',
                description: 'The ID of the education level',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 3)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of faculties for the given education level',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/FacultyResource')
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Education level not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Education] 99'),
                    ]
                )
            ),
        ]
    )]
    public function faculties(): void {}

    // ── Church responsibilities (scoped to a department) ────────────────────
    #[OA\PathItem(path: '/members/departments/{department}/church-responsibilities')]
    #[OA\Get(
        path: '/members/departments/{department}/church-responsibilities',
        description: 'Returns all church responsibilities available for the given department, ordered alphabetically. Use this to populate the church responsibility multi-select on the member registration form once a department is chosen.',
        summary: 'List church responsibilities for a department',
        tags: ['Member Form Data'],
        parameters: [
            new OA\Parameter(
                name: 'department',
                description: 'The ID of the department',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of church responsibilities for the given department',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/ChurchResponsibilityResource')
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Department not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Department] 99'),
                    ]
                )
            ),
        ]
    )]
    public function churchResponsibilities(): void {}

    // ── Departments ──────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/departments')]
    #[OA\Get(
        path: '/members/departments',
        description: 'Returns all departments ordered alphabetically. Use this to populate the department dropdown on the member registration form.',
        summary: 'List all departments',
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
