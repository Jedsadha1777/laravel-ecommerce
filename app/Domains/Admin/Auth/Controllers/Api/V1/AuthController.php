<?php

namespace App\Domains\Admin\Auth\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domains\Admin\Auth\Requests\LoginRequest;
use App\Domains\Admin\Auth\Services\AuthService;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
        $admin = Auth::guard('admin-api')->user();
        
        $result = $this->authService->logout($admin, null);
        
        return ApiResponse::success(null, $result['message']);
    }
}