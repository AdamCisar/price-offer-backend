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
        $priceOfferDto = PriceOfferMapper::toDto($request);
        $customer = $priceOfferDto->customer->toArray();

        if (!empty($request['id'])) {
            $this->customerRepository->save($customer);
            $result['customer'] = $this->priceOfferCustomerRepository->save($customer, $request['id']);
        }

        foreach ($priceOfferDto->items as $key => $priceOfferItem) {
            $result['items'][] = $this->priceOfferItemRepository->save($priceOfferItem->toArray(), $request['id']);
        }
        
        $result['id'] = $request['id'] ?? 0;
        $result['title'] = $request['title'] ?? '';
        $result['description'] = $request['description'] ?? '';
        
        if (empty($request['id'])) {
            $priceOffer = $this->priceOfferRepository->save($request);
            $result = array_replace($result, $priceOffer);
        }

        $priceOfferResultDto = PriceOfferMapper::toDto($result);

        return $priceOfferResultDto;
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