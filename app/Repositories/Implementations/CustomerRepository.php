<?php

namespace App\Repositories\Implementations;

use App\Models\Customer;
use App\Repositories\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function save(array $customer): array
    {
        $customer = Customer::updateOrCreate(
            [
                'name' => $customer['name'], 
                'address' => $customer['address'], 
                'city' => $customer['city'],
            ],
            $customer
        );

        return $customer->toArray();
    }

    public function findById(int $id): array
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return [];
        }

        return $customer->toArray();
    }
}
