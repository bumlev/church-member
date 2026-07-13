<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property mixed $id
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $email
 * @property mixed $mobile_tel
 * @property mixed $employed
 * @property mixed $fathers_name
 * @property mixed $mothers_name
 * @property mixed $fax_number
 * @property mixed $sex_id
 * @property mixed $marital_status_id
 * @property mixed $province_id
 * @property mixed $district_id
 * @property mixed $sector_id
 * @property mixed $cellule_id
 * @property mixed $cell_id
 * @property mixed $village_id
 */
#[OA\Schema(
    schema: 'MemberResource',
    title: 'Member',
    description: 'A church member record',
    properties: [
        new OA\Property(property: 'id',                 type: 'integer', example: 1),
        new OA\Property(property: 'first_name',         type: 'string',  example: 'John'),
        new OA\Property(property: 'last_name',          type: 'string',  example: 'Doe'),
        new OA\Property(
            property: 'talents',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/TalentResource')
        ),
        new OA\Property(
            property: 'spiritual_gifts',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/SpiritualGiftResource')
        ),
        new OA\Property(property: 'email',              type: 'string',  example: 'john.doe@example.com', nullable: true),
        new OA\Property(property: 'mobile_tel',         type: 'string',  example: '+250788000000',        nullable: true),
        new OA\Property(property: 'employed',           type: 'boolean', example: true),
        new OA\Property(property: 'fathers_name',       type: 'string',  example: 'James Doe',            nullable: true),
        new OA\Property(property: 'mothers_name',       type: 'string',  example: 'Mary Doe',             nullable: true),
        new OA\Property(property: 'fax_number',         type: 'string',  example: null,                   nullable: true),
        new OA\Property(property: 'sex_id',             type: 'integer', example: 1),
        new OA\Property(property: 'marital_status_id',  type: 'integer', example: 1),
        new OA\Property(
            property: 'occupations',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/OccupationResource')
        ),
        new OA\Property(
            property: 'educations',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/EducationResource')
        ),
        new OA\Property(
            property: 'faculties',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/FacultyResource')
        ),
        new OA\Property(
            property: 'departments',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/DepartmentResource')
        ),
        new OA\Property(
            property: 'church_responsibilities',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ChurchResponsibilityResource')
        ),
        new OA\Property(property: 'province_id',        type: 'integer', example: 1),
        new OA\Property(property: 'district_id',        type: 'integer', example: 1),
        new OA\Property(property: 'sector_id',          type: 'integer', example: 1),
        new OA\Property(property: 'cellule_id',         type: 'integer', example: 1),
        new OA\Property(property: 'cell_id',            type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'village_id',         type: 'integer', example: 1),
    ]
)]
class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'talents'           => TalentResource::collection($this->whenLoaded('talents')),
            'spiritual_gifts'   => SpiritualGiftResource::collection($this->whenLoaded('spiritualGifts')),
            'email'             => $this->email,
            'mobile_tel'        => $this->mobile_tel,
            'employed'          => $this->employed,
            'fathers_name'      => $this->fathers_name,
            'mothers_name'      => $this->mothers_name,
            'fax_number'        => $this->fax_number,
            'sex_id'            => $this->sex_id,
            'marital_status_id' => $this->marital_status_id,
            'occupations'       => OccupationResource::collection($this->whenLoaded('occupations')),
            'educations'        => EducationResource::collection($this->whenLoaded('educations')),
            'faculties'         => FacultyResource::collection($this->whenLoaded('faculties')),
            'departments'       => DepartmentResource::collection($this->whenLoaded('departments')),
            'church_responsibilities' => ChurchResponsibilityResource::collection($this->whenLoaded('churchResponsibilities')),
            'province_id'       => $this->province_id,
            'district_id'       => $this->district_id,
            'sector_id'         => $this->sector_id,
            'cellule_id'        => $this->cellule_id,
            'cell_id'           => $this->cell_id,
            'village_id'        => $this->village_id,
        ];
    }
}
