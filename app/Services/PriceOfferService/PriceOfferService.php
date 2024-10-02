<?php

namespace App\Services\PriceOfferService;

use App\Dto\PriceOfferDto;
use App\Mappers\PriceOfferMapper;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\PriceOffer\PriceOfferCustomerRepositoryInterface;
use App\Repositories\PriceOffer\PriceOfferItemRepositoryInterface;
use App\Repositories\PriceOffer\PriceOfferRepositoryInterface;

class PriceOfferService
{
    public function __construct(
        private PriceOfferRepositoryInterface $priceOfferRepository,
        private CustomerRepositoryInterface $customerRepository,
        private PriceOfferItemRepositoryInterface $priceOfferItemRepository,
        private PriceOfferCustomerRepositoryInterface $priceOfferCustomerRepository
        ) {}

    public function getPriceOffers(): array
    {
        return $this->priceOfferRepository->getPriceOffers();
    }

    public function createOrUpdate(array $request): PriceOfferDto
    {
        // create price offer
        if (empty($request['id'])) {
            $priceOffer = $this->priceOfferRepository->save($request);
            return PriceOfferMapper::toDto($priceOffer);
        }

        // update price offer
        $priceOfferDto = PriceOfferMapper::toDto($request);
        $customer = $priceOfferDto->customer->toArray();

        // vytvorenie zakaznika vseobecne pre vyhladavanie
        // $this->customerRepository->save($customer);

        // vytvorenie zakaznika pre cenovu ponuku
        $result['customer'] = $this->priceOfferCustomerRepository->save($customer, $request['id']);

        foreach ($priceOfferDto->items as $key => $priceOfferItem) {
            $result['items'][] = $this->priceOfferItemRepository->save($priceOfferItem->toArray(), $request['id']);
        }
        
        $result['id'] = $request['id'];
        $result['title'] = $request['title'] ?? '';
        $result['description'] = $request['description'] ?? '';

        return PriceOfferMapper::toDto($result);
    }

    public function getPriceOffer(int $id): PriceOfferDto
    {
        return $this->priceOfferRepository->findById($id);
    }

    public function delete(array $request): int
    {
        return $this->priceOfferRepository->delete($request['id']);
    }
}