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
            ['price_offer_id' => $priceOfferId],
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

    public function findCustomersByQuery(string $query): array
    {
        return PriceOfferCustomer::where('name', 'like', '%' . $query . '%')
            ->orWhere('address', 'like', '%' . $query . '%')
            ->orWhere('city', 'like', '%' . $query . '%')
            ->get()
            ->toArray();
    }
}
