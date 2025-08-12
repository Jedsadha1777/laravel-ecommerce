<?php

namespace App\Domains\Admin\Auth\Services;

// use App\Domains\Admin\Auth\Models\Admin;
use App\Domains\Admin\Auth\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login(array $credentials)
    {
        $admin = $this->adminRepository->findByEmail($credentials['email']);
        
        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return null;
        }

        // Revoke all existing tokens
        $this->revokeAllUserTokens($admin);
        
        // Create new token
        $token = $admin->createToken('AdminToken')->accessToken;

        return [
            'admin' => $this->formatAdminData($admin),
            'token' => $token,
        ];
    }

    public function logout($admin = null, ?string $bearerToken = null)
    {
        if ($admin) {
            if ($admin->token()) {
                $admin->token()->revoke();
            }
            $this->revokeAllUserTokens($admin);
            
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

    protected function revokeAllUserTokens($admin)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $admin->id)
            ->where('name', 'AdminToken')
            ->where('revoked', false)
            ->update(['revoked' => true]);
    }

    protected function formatAdminData($admin)
    {
        return [
            'uuid' => $admin->uuid,
            'name' => $admin->name,
            'email' => $admin->email,
        ];
    }
}