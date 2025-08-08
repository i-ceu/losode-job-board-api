<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public AuthService $authService;

    public function __construct()
    {

        $this->authService = app(AuthService::class);
    }


    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $token = $this->authService->login($data);

        return response()->json([
            'token' => $token,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $user = $this->authService->register($data);

        return response()->json([
            'status' => "success",
            'message' => 'User Createad Successful',
            'data' => new UserResource($user),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'ststus'=> 'success',
            'message' => 'Successfully looged out user.'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
