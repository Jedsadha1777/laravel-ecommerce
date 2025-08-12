<?php

namespace App\Domains\Shop\User\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domains\Shop\User\Requests\LoginRequest;
use App\Domains\Shop\User\Requests\RegisterRequest;
use App\Domains\Shop\User\Services\AuthService;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        
        return ApiResponse::success($result, 'Registration successful', 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        
        if (!$result) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        return ApiResponse::success($result, 'Login successful');
    }

    public function logout()
    {     
        $user = Auth::guard('api')->user();
        
        $result = $this->authService->logout($user, null);
        
        return ApiResponse::success(null, $result['message']);
    }

    public function profile()
    {
        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return ApiResponse::error('Unauthenticated', 401);
        }
        
        $profile = $this->authService->getProfile($user);
        
        return ApiResponse::success($profile, 'Profile retrieved successfully');
    }
}