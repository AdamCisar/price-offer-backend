<?php

namespace App\Providers;

use App\Repositories\Implementations\ItemRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Implementations\PriceOfferRepository;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\PriceOfferRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PriceOfferRepositoryInterface::class, PriceOfferRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
