<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse {

        try {

            $validated = $request->validate([
                'username' => 'required|string|min:3|max:50|unique:users,username',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string',
            ]);

        } catch (ValidationException $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);

        }

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => ['id' => $user->id, 'username' => $user->username],
                'authentication_token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse {

        try {

            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $user = User::where('username', $validated['username'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password',
            ], 401);
        }

        // delete all old token
        $user->tokens()->delete();

        $authToken = $user->createToken('auth_token')->plainTextToken;
        $refreshToken = $user->createToken('refresh_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => ['id' => $user->id, 'username' => $user->username],
                'authentication_token' => $authToken,
                'refresh_token' => $refreshToken,
            ],
        ]);

    }
}