<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {}

    public function login(LoginRequest $request): JsonResponse
    {
        ['user' => $user, 'token' => $token] = $this->authService->login($request->validated());

        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token,
        ], ResponseAlias::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
