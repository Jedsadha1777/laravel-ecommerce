<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->registerPolicies();

        // ตั้งอายุ token ตามต้องการ
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        // Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // ถ้าต้องการ scopes:
        // Passport::tokensCan([
        //     'view-catalog' => 'View product catalog',
        // ]);
    }
}
