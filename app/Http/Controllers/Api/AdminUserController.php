<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdminUserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly UserService $userService)
    {}

    public function index(): JsonResponse
    {
        return UserResource::collection(
            $this->userService->getAllUsers()
        )->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return (new UserResource($user))
            ->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        ['user' => $user, 'temporary_password' => $temporaryPassword] = $this->userService->createUser($request->validated());

        return response()->json([
            'user'               => new UserResource($user),
            'temporary_password' => $temporaryPassword,
        ], ResponseAlias::HTTP_CREATED);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        return (new UserResource(
            $this->userService->updateUser($user, $request->validated())
        ))->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->userService->deleteUser($user);

        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        $this->authorize('updateRole', $user);

        return (new UserResource(
            $this->userService->updateRole($user, $request->validated('role'))
        ))->response()->setStatusCode(ResponseAlias::HTTP_OK);
    }
}
