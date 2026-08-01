<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PasswordResetController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {}

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->sendResetLink($request->validated('email'));

        return response()->json([
            'message' => 'If an account with that email exists, a password reset link has been sent.',
        ], ResponseAlias::HTTP_OK);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword($request->validated());

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status),
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'message' => 'Your password has been reset successfully.',
        ], ResponseAlias::HTTP_OK);
    }
}
