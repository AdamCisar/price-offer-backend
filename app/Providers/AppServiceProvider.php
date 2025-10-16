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
use App\Services\Scrappers\Eshops\PtacekScrapper;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
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

        $this->app->singleton(PtacekScrapper::class, function ($app) {
            $jar = new CookieJar();
            $client = new Client([
                'cookies' => $jar,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' .
                                    '(KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'sk-SK,sk;q=0.9,en-US;q=0.8,en;q=0.7',
                ],
            ]);

            return new PtacekScrapper($client, $jar);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
