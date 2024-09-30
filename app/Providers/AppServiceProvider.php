<?php

namespace App\Providers;

use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\Implementations\CustomerRepository;
use App\Repositories\Implementations\ItemRepository;
use App\Repositories\Implementations\PriceOffer\PriceOfferCustomerRepository;
use App\Repositories\Implementations\PriceOffer\PriceOfferItemRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Implementations\PriceOffer\PriceOfferRepository;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\PriceOffer\PriceOfferCustomerRepositoryInterface;
use App\Repositories\PriceOffer\PriceOfferItemRepositoryInterface;
use App\Repositories\PriceOffer\PriceOfferRepositoryInterface;
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
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(PriceOfferItemRepositoryInterface::class, concrete: PriceOfferItemRepository::class);
        $this->app->bind(PriceOfferCustomerRepositoryInterface::class, concrete: PriceOfferCustomerRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
