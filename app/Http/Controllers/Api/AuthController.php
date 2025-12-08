<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Enums\HttpStatus;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $abilities = $user->role === 0 ? ['*'] : [];

        $token = $user->createToken('default', $abilities)->plainTextToken;

        return $this->successResponse(
            ['user' => $user, 'token' => $token],
            'User registered successfully',
            HttpStatus::CREATED->value
        );
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(
                'Invalid credentials',
                ['username' => ['Invalid username or password']],
                HttpStatus::UNAUTHORIZED->value
            );
        }

        $abilities = $user->role === 0 ? ['*'] : [];

        $token = $user->createToken('default', $abilities)->plainTextToken;

        return $this->successResponse(
            ['user' => $user, 'token' => $token],
            'User logged in successfully'
        );
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(
            null,
            'User logged out successfully'
        );
    }
}
