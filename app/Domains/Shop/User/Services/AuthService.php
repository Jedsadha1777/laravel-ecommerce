<?php

namespace App\Domains\Shop\User\Services;

use App\Domains\Shop\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        $user = $this->userRepository->create($userData);
        
        // Revoke any existing tokens (if any)
        $this->revokeAllUserTokens($user);
        
        $token = $user->createToken('UserToken')->accessToken;

        return [
            'user' => $this->formatUserData($user),
            'token' => $token,
        ];
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        // Revoke all existing tokens
        $this->revokeAllUserTokens($user);
        
        // Create new token
        $token = $user->createToken('UserToken')->accessToken;

        return [
            'user' => $this->formatUserData($user),
            'token' => $token,
        ];
    }

    public function logout($user = null, ?string $bearerToken = null)
    {
        if ($user) {
            if ($user->token()) {
                $user->token()->revoke();
            }
            $this->revokeAllUserTokens($user);
            
            return [
                'success' => true,
                'message' => 'Logout successful',
            ];
        }

        // ถ้าไม่มี admin แต่มี token - ไม่ทำอะไร (ป้องกันการ logout คนอื่น)
        // แต่ return success เพื่อไม่ให้รู้ว่า token ไม่ valid
        return [
            'success' => true,
            'message' => 'Logout processed',
        ];
    }

    public function getProfile($user)
    {
        return $this->formatUserData($user);
    }


    protected function revokeAllUserTokens($user)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)
            ->where('name', 'UserToken')
            ->where('revoked', false)
            ->update(['revoked' => true]);
    }

    protected function formatUserData($user)
    {
        return [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
        ];
    }
}