<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChurchResponsibilityResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\MemberResource;
use App\Http\Resources\OccupationResource;
use App\Http\Resources\SpiritualGiftResource;
use App\Models\Department;
use App\Models\Education;
use App\Services\ChurchResponsibilityService;
use App\Services\DepartmentService;
use App\Services\EducationService;
use App\Http\Resources\TalentResource;
use App\Services\FacultyService;
use App\Http\Requests\CheckMemberExistsRequest;
use App\Http\Requests\FilterMemberRequest;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Services\MemberService;
use App\Services\OccupationService;
use App\Services\SpiritualGiftService;
use App\Services\TalentService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MemberController extends Controller
{
    public function __construct(
        private readonly OccupationService $occupationService,
        private readonly EducationService  $educationService,
        private readonly DepartmentService $departmentService,
        private readonly FacultyService $facultyService,
        private readonly ChurchResponsibilityService $churchResponsibilityService,
        private readonly TalentService $talentService,
        private readonly SpiritualGiftService $spiritualGiftService,
        private readonly MemberService $memberService
    ) {}


    public function testApi(): JsonResponse
    {
        return response()->json('test api', ResponseAlias::HTTP_OK);
    }

    public function index(FilterMemberRequest $request): JsonResponse
    {
        return MemberResource::collection(
            $this->memberService->filterMembers($request->validated())
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function show(Member $member): JsonResponse
    {
        return (new MemberResource(
            $this->memberService->getMemberById($member->id)
        ))->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function exists(CheckMemberExistsRequest $request): JsonResponse
    {
        return MemberResource::collection(
            $this->memberService->findPotentialDuplicates($request->validated())
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function store(StoreMemberRequest $request): JsonResponse
    {
        $member = $this->memberService->createMember($request->validated());
        return (new MemberResource($member)
        )->response()->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    public function update(UpdateMemberRequest $request, Member $member): JsonResponse
    {
        return (new MemberResource(
            $this->memberService->updateMember($member, $request->validated())
        ))->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function occupations(): JsonResponse
    {
        return OccupationResource::collection(
            $this->occupationService->getAll()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function educations(): JsonResponse
    {
        return EducationResource::collection(
            $this->educationService->getAll()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }


    public function departments(): JsonResponse
    {
        return DepartmentResource::collection(
            $this->departmentService->getAll()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function faculties(Education $education): JsonResponse
    {
        return FacultyResource::collection(
            $this->facultyService->getByEducation($education)
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function churchResponsibilities(Department $department): JsonResponse
    {
        return ChurchResponsibilityResource::collection(
            $this->churchResponsibilityService->getByDepartment($department)
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function talents(): JsonResponse
    {
        return TalentResource::collection(
            $this->talentService->getAll()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function spiritualGifts(): JsonResponse
    {
        return SpiritualGiftResource::collection(
            $this->spiritualGiftService->getAll()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }
}


