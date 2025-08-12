<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class DomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Admin Auth Service
        $this->app->bind(
            \App\Domains\Admin\Auth\Services\AuthService::class,
            function ($app) {
                return new \App\Domains\Admin\Auth\Services\AuthService(
                    new \App\Domains\Admin\Auth\Repositories\AdminRepository(
                        new \App\Domains\Admin\Auth\Models\Admin()
                    )
                );
            }
        );

        // Admin Product Service
        $this->app->bind(
            \App\Domains\Admin\Product\Services\ProductService::class,
            function ($app) {
                return new \App\Domains\Admin\Product\Services\ProductService(
                    new \App\Domains\Admin\Product\Repositories\ProductRepository(
                        new \App\Domains\Shared\Product\Models\Product()
                    )
                );
            }
        );

        // Shop User Auth Service
        $this->app->bind(
            \App\Domains\Shop\User\Services\AuthService::class,
            function ($app) {
                return new \App\Domains\Shop\User\Services\AuthService(
                    new \App\Domains\Shop\User\Repositories\UserRepository(
                        new \App\Domains\Shop\User\Models\User()
                    )
                );
            }
        );

        // Shop Catalog Service
        $this->app->bind(
            \App\Domains\Shop\Catalog\Services\CatalogService::class,
            function ($app) {
                return new \App\Domains\Shop\Catalog\Services\CatalogService(
                    new \App\Domains\Shop\Catalog\Repositories\ProductRepository(
                        new \App\Domains\Shared\Product\Models\Product()
                    )
                );
            }
        );

        // Shop Order Service - Alternative cleaner approach
        $this->app->bind(
            \App\Domains\Shop\Order\Services\OrderService::class,
            function ($app) {
                return new \App\Domains\Shop\Order\Services\OrderService(
                    $app->make(\App\Domains\Shop\Order\Repositories\OrderRepository::class),
                    $app->make(\App\Domains\Shop\Catalog\Repositories\ProductRepository::class)
                );
            }
        );

        // Shop Order Service bind repositories แยก
        $this->app->bind(
            \App\Domains\Shop\Order\Repositories\OrderRepository::class,
            function ($app) {
                return new \App\Domains\Shop\Order\Repositories\OrderRepository(
                    new \App\Domains\Shop\Order\Models\Order()
                );
            }
        );

        $this->app->bind(
            \App\Domains\Shop\Catalog\Repositories\ProductRepository::class,
            function ($app) {
                return new \App\Domains\Shop\Catalog\Repositories\ProductRepository(
                    new \App\Domains\Shared\Product\Models\Product()
                );
            }
        );
    }

    public function boot()
    {
        // Register policies
        Gate::policy(\App\Domains\Shared\Product\Models\Product::class, \App\Domains\Admin\Product\Policies\ProductPolicy::class);
        Gate::policy(\App\Domains\Shop\Order\Models\Order::class, \App\Domains\Shop\Order\Policies\OrderPolicy::class);
    }
}