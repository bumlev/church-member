<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API documentation for the Church Member Management System. Covers geographic data (provinces, districts, sectors, cellules, villages) and member management.',
    title: 'Church Member Management API',
    contact: new OA\Contact(
        name: 'Church Member API Support',
        email: 'support@church-member.local'
    )
)]
#[OA\Server(
    url: '/api/v1',
    description: 'API v1'
)]
#[OA\Tag(name: 'Provinces',        description: 'Operations related to provinces')]
#[OA\Tag(name: 'Districts',        description: 'Operations related to districts')]
#[OA\Tag(name: 'Sectors',          description: 'Operations related to sectors')]
#[OA\Tag(name: 'Cellules',         description: 'Operations related to cellules')]
#[OA\Tag(name: 'Villages',         description: 'Operations related to villages')]
#[OA\Tag(name: 'Members',          description: 'Operations related to church members')]
#[OA\Tag(name: 'Member Form Data', description: 'Lookup lists used to populate member registration form dropdowns')]
class ApiInfo {}



