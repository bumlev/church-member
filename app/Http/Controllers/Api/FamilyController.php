<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFamilyMemberRequest;
use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use App\Models\Member;
use App\Services\FamilyService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FamilyController extends Controller
{
    public function __construct(
        private readonly FamilyService $familyService
    ) {}

    public function index(): JsonResponse
    {
        return FamilyResource::collection(
            $this->familyService->getAllFamilies()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function show(Family $family): JsonResponse
    {
        return (new FamilyResource(
            $this->familyService->getFamilyById($family->id)
        ))->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function store(StoreFamilyRequest $request): JsonResponse
    {
        $family = $this->familyService->createFamily($request->validated());
        return (new FamilyResource($family)
        )->response()->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    public function update(UpdateFamilyRequest $request, Family $family): JsonResponse
    {
        return (new FamilyResource(
            $this->familyService->updateFamily($family, $request->validated())
        ))->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function destroy(Family $family): JsonResponse
    {
        $family->delete();
        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }

    public function addMember(AddFamilyMemberRequest $request, Family $family): JsonResponse
    {
        $this->familyService->addMember($family, $request->validated());

        return (new FamilyResource(
            $this->familyService->getFamilyById($family->id)
        ))->response()->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    public function removeMember(Family $family, Member $member): JsonResponse
    {
        $this->familyService->removeMember($family, $member->id);
        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
