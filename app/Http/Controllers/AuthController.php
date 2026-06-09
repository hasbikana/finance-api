<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $service
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->service->register($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->service->login($request->email, $request->password);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.',
                'data' => null,
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
            'data' => null,
        ], 200);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Data profil berhasil diambil.',
            'data' => [
                'user' => new UserResource($request->user()),
            ],
        ], 200);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->user()->id)],
        ]);

        $user = $this->service->updateProfile($request->user(), $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data' => ['user' => new UserResource($user)],
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $success = $this->service->updatePassword(
            $request->user(),
            $validated['current_password'],
            $validated['password']
        );

        if (!$success) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password lama tidak sesuai.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}
