<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\DosenResource;
use App\Http\Resources\MahasiswaResource;
use App\Models\Dosen;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function login(AdminLoginRequest $request): JsonResponse
    {
        [$user, $token] = $this->auth->loginAdmin(
            $request->integer('nomor_induk'),
            $request->string('password')->value(),
        );

        $resource = $user instanceof Dosen
            ? new DosenResource($user)
            : new MahasiswaResource($user);

        return response()->json([
            'user' => $resource,
            'token' => $token,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        $resource = $user instanceof Dosen
            ? new DosenResource($user)
            : new MahasiswaResource($user);

        return response()->json(['user' => $resource]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());

        return response()->json(['message' => 'Berhasil logout.']);
    }
}
