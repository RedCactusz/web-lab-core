<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientLoginRequest;
use App\Http\Resources\MahasiswaResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function login(ClientLoginRequest $request): JsonResponse
    {
        [$mahasiswa, $token] = $this->auth->loginClient(
            $request->integer('nim'),
            $request->string('password')->value(),
        );

        return response()->json([
            'user' => new MahasiswaResource($mahasiswa),
            'token' => $token,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new MahasiswaResource($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());

        return response()->json(['message' => 'Berhasil logout.']);
    }
}
