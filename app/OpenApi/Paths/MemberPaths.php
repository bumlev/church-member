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
        description: 'Returns a collection of church members ordered by last name then first name, optionally narrowed down by any combination of the filter query parameters below. Omitting all filters returns every member.',
        summary: 'List / filter members',
        security: [['sanctum' => []]],
        tags: ['Members'],
        parameters: [
            new OA\Parameter(name: 'first_name', description: 'Partial match on first name.', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: 'John')),
            new OA\Parameter(name: 'last_name', description: 'Partial match on last name.', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: 'Doe')),
            new OA\Parameter(name: 'fathers_name', description: 'Partial match on father\'s name.', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: 'James')),
            new OA\Parameter(name: 'mothers_name', description: 'Partial match on mother\'s name.', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: 'Mary')),
            new OA\Parameter(name: 'national_id', description: 'Exact match on national ID.', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: '1199080012345678')),
            new OA\Parameter(
                name: 'sex_id',
                description: 'One or more sex IDs. Repeat the param (sex_id[]=1&sex_id[]=2), send a comma-separated list ("1,2"), or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'marital_status_id',
                description: 'One or more marital status IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(name: 'age_min', description: 'Minimum age (inclusive), derived from date_birthday.', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 18)),
            new OA\Parameter(name: 'age_max', description: 'Maximum age (inclusive), derived from date_birthday.', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 35)),
            new OA\Parameter(name: 'date_birthday_from', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '1990-01-01')),
            new OA\Parameter(name: 'date_birthday_to', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2000-12-31')),
            new OA\Parameter(name: 'date_salvation_from', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2000-01-01')),
            new OA\Parameter(name: 'date_salvation_to', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2020-12-31')),
            new OA\Parameter(name: 'date_baptism_from', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2000-01-01')),
            new OA\Parameter(name: 'date_baptism_to', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2020-12-31')),
            new OA\Parameter(name: 'member_since_from', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2010-01-01')),
            new OA\Parameter(name: 'member_since_to', description: 'Format: yyyy-mm-dd.', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date', example: '2020-12-31')),
            new OA\Parameter(name: 'employed', description: 'Exact match. Accepts "true"/"false" (also "1"/"0").', in: 'query', required: false, schema: new OA\Schema(type: 'boolean', example: true)),
            new OA\Parameter(
                name: 'province_id',
                description: 'One or more province IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'district_id',
                description: 'One or more district IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'sector_id',
                description: 'One or more sector IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'cellule_id',
                description: 'One or more cellule IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'cell_id',
                description: 'One or more cell IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'village_id',
                description: 'One or more village IDs. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1])
            ),
            new OA\Parameter(
                name: 'occupation_id',
                description: 'Member has any of these occupations. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [2])
            ),
            new OA\Parameter(
                name: 'talent_id',
                description: 'Member has any of these talents. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [4, 7])
            ),
            new OA\Parameter(
                name: 'spiritual_gift_id',
                description: 'Member has any of these spiritual gifts. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [1, 3])
            ),
            new OA\Parameter(
                name: 'education_id',
                description: 'Member has any of these education levels. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [3])
            ),
            new OA\Parameter(
                name: 'faculty_id',
                description: 'Member has any of these faculties. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [4, 5])
            ),
            new OA\Parameter(
                name: 'department_id',
                description: 'Member belongs to any of these departments. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [2])
            ),
            new OA\Parameter(
                name: 'church_responsibility_id',
                description: 'Member has any of these church responsibilities. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [5])
            ),
            new OA\Parameter(
                name: 'family_id',
                description: 'Member belongs to any of these families. Repeat the param, send a comma-separated list, or a single ID.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), example: [10])
            ),
            new OA\Parameter(
                name: 'family_role_type',
                description: 'Member holds this role within a family membership. One of: father, mother, child, guardian.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['father', 'mother', 'child', 'guardian'], example: 'child')
            ),
            new OA\Parameter(name: 'page', description: 'Page number to retrieve.', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, default: 1, example: 1)),
            new OA\Parameter(name: 'per_page', description: 'Number of members per page (max 100).', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, default: 20, example: 20)),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 200,
                description: 'A paginated list of church members',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/MemberResource')
                        ),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error in one or more filter parameters',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Selected sex is invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['sex_id.0' => ['Selected sex is invalid.']]
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
        description: 'Creates a new church member record. Returns the created member. Submitted as multipart/form-data to support the optional picture upload.',
        summary: 'Create a new member',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                required: ['first_name', 'last_name', 'talent', 'spiritual_gift', 'sex_id', 'marital_status_id', 'date_birthday'],
                properties: [
                    new OA\Property(property: 'first_name',        type: 'string',  example: 'John',          maxLength: 100),
                    new OA\Property(property: 'last_name',         type: 'string',  example: 'Doe',           maxLength: 100),
                    new OA\Property(
                        property: 'talent',
                        description: 'IDs of talents selected (at least one required). Submitted as multipart/form-data: use repeated fields (talent[]=1&talent[]=2), a single JSON-encoded string (e.g. "[1,2]"), a comma-separated string (e.g. "1,2"), or a bare single ID (e.g. talent=1).',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 2],
                        minItems: 1
                    ),
                    new OA\Property(
                        property: 'spiritual_gift',
                        description: 'IDs of spiritual gifts selected (at least one required). Submitted as multipart/form-data: use repeated fields (spiritual_gift[]=1&spiritual_gift[]=3), a single JSON-encoded string (e.g. "[1,3]"), a comma-separated string (e.g. "1,3"), or a bare single ID (e.g. spiritual_gift=1).',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 3],
                        minItems: 1
                    ),
                    new OA\Property(property: 'sex_id',            type: 'integer', example: 1),
                    new OA\Property(property: 'marital_status_id', type: 'integer', example: 1),
                    new OA\Property(property: 'fathers_name',      type: 'string',  example: 'James Doe',     nullable: true, maxLength: 150),
                    new OA\Property(property: 'mothers_name',      type: 'string',  example: 'Mary Doe',      nullable: true, maxLength: 150),
                    new OA\Property(property: 'employed',          type: 'boolean', example: true,            nullable: true, description: 'Submitted as multipart/form-data as the literal string "true" or "false" (also accepts "1"/"0").'),
                    new OA\Property(
                        property: 'occupation',
                        description: 'IDs of occupations selected. Submitted as multipart/form-data: use repeated fields (occupation[]=2), a single JSON-encoded string (e.g. "[2]"), a comma-separated string (e.g. "2,4"), or a bare single ID (e.g. occupation=2).',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [2],
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'education',
                        description: 'Education levels selected, each paired with its own faculty IDs. This is an array of objects, which cannot be expressed as a plain multipart/form-data field — submit it as a single JSON-encoded string, e.g. [{"education_id":3,"faculty":[4,5]}].',
                        type: 'string',
                        example: '[{"education_id":3,"faculty":[4,5]}]',
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'department',
                        description: 'Departments selected, each paired with its own church responsibility IDs. This is an array of objects, which cannot be expressed as a plain multipart/form-data field — submit it as a single JSON-encoded string, e.g. [{"department_id":1,"church_responsibility":[1,2]}].',
                        type: 'string',
                        example: '[{"department_id":1,"church_responsibility":[1,2]}]',
                        nullable: true
                    ),
                    new OA\Property(property: 'mobile_tel',        type: 'string',  example: '+250788000000', nullable: true, maxLength: 20),
                    new OA\Property(property: 'email',             type: 'string',  format: 'email', example: 'john@example.com', nullable: true, maxLength: 150),
                    new OA\Property(property: 'national_id',       type: 'string',  example: '1199080012345678', nullable: true, maxLength: 20),
                    new OA\Property(property: 'picture', description: 'Image file (jpeg, jpg, png, webp), max 2MB', type: 'string', format: 'binary', nullable: true),
                    new OA\Property(property: 'date_birthday', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '1990-05-12'),
                    new OA\Property(property: 'date_salvation', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '2005-03-20', nullable: true),
                    new OA\Property(property: 'date_baptism', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '2005-06-15', nullable: true),
                    new OA\Property(property: 'member_since', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '2010-01-01', nullable: true),
                    new OA\Property(property: 'province_id',       type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'district_id',       type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'sector_id',         type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'cellule_id',        type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'cell_id',           type: 'integer', example: 1,               nullable: true),
                    new OA\Property(property: 'village_id',        type: 'integer', example: 1,               nullable: true),
                ]
                )
            )
        ),
        tags: ['Members'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
        security: [['sanctum' => []]],
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
            new OA\Response(response: 401, description: 'Unauthenticated'),
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

    // ── Live duplicate check ─────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/exists')]
    #[OA\Get(
        path: '/members/exists',
        description: 'Looks up members whose first name, last name, and date of birth exactly match the given values. Intended for a live check on the member registration form, fired once all three fields are filled, to warn staff before they create a likely-duplicate record. Returns an empty list when no match is found.',
        summary: 'Check whether a member with this identity already exists',
        security: [['sanctum' => []]],
        tags: ['Members'],
        parameters: [
            new OA\Parameter(name: 'first_name', description: 'Exact match on first name.', in: 'query', required: true, schema: new OA\Schema(type: 'string', example: 'John')),
            new OA\Parameter(name: 'last_name', description: 'Exact match on last name.', in: 'query', required: true, schema: new OA\Schema(type: 'string', example: 'Doe')),
            new OA\Parameter(name: 'date_birthday', description: 'Format: yyyy-mm-dd.', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date', example: '1990-05-12')),
        ],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 200,
                description: 'Members matching the given identity (may be empty)',
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
            new OA\Response(
                response: 422,
                description: 'Validation error in one or more of the identity fields',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'First name is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            example: ['first_name' => ['First name is required.']]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function exists(): void {}

    // ── Update a member ──────────────────────────────────────────────────────
    #[OA\Put(
        path: '/members/{id}',
        description: 'Updates a church member record. All fields are optional (PATCH behaviour). Returns the updated member. Passing a new `picture` replaces and deletes the previous one; omitting it leaves the current picture untouched. Submitted as multipart/form-data to support the picture upload — since PHP does not parse multipart bodies on PUT requests, send this as a POST with a `_method=PUT` field (Laravel\'s standard method-spoofing).',
        summary: 'Update an existing member',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'John', nullable: true, maxLength: 100),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe', nullable: true, maxLength: 100),
                    new OA\Property(
                        property: 'talent',
                        description: 'IDs of talents selected (at least one required if provided). Submitted as multipart/form-data: use repeated fields (talent[]=1&talent[]=2), a single JSON-encoded string (e.g. "[1,2]"), a comma-separated string (e.g. "1,2"), or a bare single ID (e.g. talent=1).',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 2],
                        nullable: true,
                        minItems: 1
                    ),
                    new OA\Property(
                        property: 'spiritual_gift',
                        description: 'IDs of spiritual gifts selected (at least one required if provided). Submitted as multipart/form-data: use repeated fields (spiritual_gift[]=1&spiritual_gift[]=3), a single JSON-encoded string (e.g. "[1,3]"), a comma-separated string (e.g. "1,3"), or a bare single ID (e.g. spiritual_gift=1).',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 3],
                        nullable: true,
                        minItems: 1
                    ),
                    new OA\Property(property: 'sex_id',            type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'marital_status_id', type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'fathers_name',      type: 'string',  example: 'James Doe',        nullable: true, maxLength: 150),
                    new OA\Property(property: 'mothers_name',      type: 'string',  example: 'Mary Doe',         nullable: true, maxLength: 150),
                    new OA\Property(property: 'employed',          type: 'boolean', example: true,               nullable: true, description: 'Submitted as multipart/form-data as the literal string "true" or "false" (also accepts "1"/"0").'),
                    new OA\Property(
                        property: 'occupation',
                        description: 'IDs of occupations selected. Submitted as multipart/form-data: use repeated fields (occupation[]=2), a single JSON-encoded string (e.g. "[2]"), a comma-separated string (e.g. "2,4"), or a bare single ID (e.g. occupation=2).',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [2],
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'education',
                        description: 'Education levels selected, each paired with its own faculty IDs. This is an array of objects, which cannot be expressed as a plain multipart/form-data field — submit it as a single JSON-encoded string, e.g. [{"education_id":3,"faculty":[4,5]}].',
                        type: 'string',
                        example: '[{"education_id":3,"faculty":[4,5]}]',
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'department',
                        description: 'Departments selected, each paired with its own church responsibility IDs. This is an array of objects, which cannot be expressed as a plain multipart/form-data field — submit it as a single JSON-encoded string, e.g. [{"department_id":1,"church_responsibility":[1,2]}].',
                        type: 'string',
                        example: '[{"department_id":1,"church_responsibility":[1,2]}]',
                        nullable: true
                    ),
                    new OA\Property(property: 'mobile_tel',        type: 'string',  example: '+250788000000',    nullable: true, maxLength: 20),
                    new OA\Property(property: 'email',             type: 'string',  format: 'email', example: 'john@example.com', nullable: true, maxLength: 150),
                    new OA\Property(property: 'national_id',       type: 'string',  example: '1199080012345678', nullable: true, maxLength: 20),
                    new OA\Property(property: 'picture', description: 'Image file (jpeg, jpg, png, webp), max 2MB', type: 'string', format: 'binary', nullable: true),
                    new OA\Property(property: 'date_birthday', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '1990-05-12', nullable: true),
                    new OA\Property(property: 'date_salvation', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '2005-03-20', nullable: true),
                    new OA\Property(property: 'date_baptism', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '2005-06-15', nullable: true),
                    new OA\Property(property: 'member_since', description: 'Format: yyyy-mm-dd', type: 'string', format: 'date', example: '2010-01-01', nullable: true),
                    new OA\Property(property: 'province_id',       type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'district_id',       type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'sector_id',         type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'cellule_id',        type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'cell_id',           type: 'integer', example: 1,                  nullable: true),
                    new OA\Property(property: 'village_id',        type: 'integer', example: 1,                  nullable: true),
                ]
                )
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
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
        security: [['sanctum' => []]],
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
        security: [['sanctum' => []]],
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
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

    // ── Spiritual gifts ──────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/spiritual-gifts')]
    #[OA\Get(
        path: '/members/spiritual-gifts',
        description: 'Returns all spiritual gifts ordered alphabetically. Use this to populate the spiritual gift multi-select on the member registration form.',
        summary: 'List all spiritual gifts',
        security: [['sanctum' => []]],
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(
                response: 200,
                description: 'A list of spiritual gifts',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/SpiritualGiftResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function spiritualGifts(): void {}

    // ── Educations ───────────────────────────────────────────────────────────
    #[OA\PathItem(path: '/members/educations')]
    #[OA\Get(
        path: '/members/educations',
        description: 'Returns all education levels ordered alphabetically. Use this to populate the education dropdown on the member registration form.',
        summary: 'List all education levels',
        security: [['sanctum' => []]],
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
        security: [['sanctum' => []]],
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
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
        security: [['sanctum' => []]],
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
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
        security: [['sanctum' => []]],
        tags: ['Member Form Data'],
        responses: [
            new OA\Response(response: 401, description: 'Unauthenticated'),
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
