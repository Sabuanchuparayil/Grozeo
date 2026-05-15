<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $bindings = [
            \App\Contracts\OrderServiceInterface::class => \App\Services\OrderService::class,
            \App\Contracts\CartServiceInterface::class => \App\Services\CartService::class,
            \App\Contracts\CustomerServiceInterface::class => \App\Services\CustomerService::class,
            \App\Contracts\ProductServiceInterface::class => \App\Services\ProductService::class,
            \App\Contracts\NotificationServiceInterface::class => \App\Services\NotificationService::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
