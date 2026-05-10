<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\OccupationResource;
use App\Services\DepartmentService;
use App\Services\EducationService;
use App\Services\OccupationService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MemberController extends Controller
{
    public function __construct(
        private readonly OccupationService $occupationService,
        private readonly EducationService  $educationService,
        private readonly DepartmentService $departmentService,
    ) {}

    /**
     * Return all occupations for the member registration form dropdown.
     */
    public function occupations(): AnonymousResourceCollection
    {
        return OccupationResource::collection(
            $this->occupationService::getAll()
        );
    }

    /**
     * Return all education levels for the member registration form dropdown.
     */
    public function educations(): AnonymousResourceCollection
    {
        return EducationResource::collection(
            $this->educationService::getAll()
        );
    }

    /**
     * Return all departments for the member registration form dropdown.
     */
    public function departments(): AnonymousResourceCollection
    {
        return DepartmentResource::collection(
            $this->departmentService::getAll()
        );
    }
}


