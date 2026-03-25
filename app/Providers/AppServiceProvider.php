<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DAO\ProductDAO;
use App\DAO\ProductDAOInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductDAOInterface::class, ProductDAO::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
