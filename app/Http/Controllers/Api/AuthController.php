<?php

namespace App\Http\Controllers\Api;

use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $dto = new RegisterDTO(
            $validated['name'],
            $validated['email'],
            $validated['password'],
            $validated['role'],
        );

        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);

        $token = JWTAuth::attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $dto = new LoginDTO(
            email: $validated['email'],
            password: $validated['password'],
        );

        $credentials = [
            'email' => $dto->email,
            'password' => $dto->password,
        ];

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => JWTAuth::user(),
            'token' => $token,
        ], 200);
    }

    public function me()
    {
        return response()->json([
            'user' => JWTAuth::user(),
        ], 200);
    }

    public function logout()
    {
        JWTAuth::parseToken()->invalidate();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
}