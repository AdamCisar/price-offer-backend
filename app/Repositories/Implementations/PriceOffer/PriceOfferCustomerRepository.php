<?php

namespace App\Repositories\Implementations\PriceOffer;

use App\Models\PriceOffer\PriceOfferCustomer;
use App\Repositories\PriceOffer\PriceOfferCustomerRepositoryInterface;

class PriceOfferCustomerRepository implements PriceOfferCustomerRepositoryInterface
{
    public function save(array $customer, int $priceOfferId): array
    {
        $customer['price_offer_id'] = $priceOfferId;
        
        $customer = PriceOfferCustomer::updateOrCreate(
            ['id' => $customer['id'] ?? null],
            $customer
        );

        return $customer->toArray();
    }

    public function findById(int $id): array
    {
        $customer = PriceOfferCustomer::find($id);

        if (!$customer) {
            return [];
        }

        return $customer->toArray();
    }
}
