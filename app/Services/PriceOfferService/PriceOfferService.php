<?php

namespace App\Services\PriceOfferService;

use App\Dto\PriceOfferCustomerDto;
use App\Dto\PriceOfferDto;
use App\Mappers\PriceOfferMapper;
use App\Models\PriceOffer\PriceOfferItem;
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

    public function getPriceOffers(int $offset): array
    {
        return $this->priceOfferRepository->getPriceOffers($offset);
    }

    public function createOrUpdate(array $request): PriceOfferDto
    {
        $priceOffer = $this->priceOfferRepository->save($request);
        $this->duplicatePriceOffer($request['duplicateFromId'] ?? 0, $priceOffer['id']);

        return PriceOfferMapper::toDto($priceOffer);
    }

    public function updatePriceOfferDetails(array $request): PriceOfferDto
    {
        $priceOfferDto = PriceOfferMapper::toDto($request);
        $customer = $priceOfferDto->customer->toArray();

        // vytvorenie alebo update zakaznika pre cenovu ponuku
        $result['customer'] = $this->priceOfferCustomerRepository->save($customer, $request['id']);

        foreach ($priceOfferDto->items as $key => $priceOfferItem) {
            $result['items'][] = $this->priceOfferItemRepository->save($priceOfferItem->toArray(), $request['id']);
        }

        $itemIdList = array_column($result['items'] ?? [], 'id');
        $this->priceOfferItemRepository->deleteNotIncluded($itemIdList, $request['id']);

        $priceOffer = $this->priceOfferRepository->save(['id' => $priceOfferDto->id, 'title' => $priceOfferDto->title, 'description' => $priceOfferDto->description, 'is_vat' => $priceOfferDto->is_vat, 'notes' => $priceOfferDto->notes]);
        
        $result = array_merge($result, $priceOffer);

        return PriceOfferMapper::toDto($result);
    }

    public function duplicatePriceOffer(int $fromPriceOfferId, int $toPriceOfferId): void
    {
        if (!$fromPriceOfferId || !$toPriceOfferId) {
            return;
        }
        
        $this->priceOfferItemRepository->duplicate($fromPriceOfferId, $toPriceOfferId);
    }

    public function getPriceOffer(int $id): PriceOfferDto
    {
        return $this->priceOfferRepository->findById($id);
    }

    public function delete(array $request): int
    {
        return $this->priceOfferRepository->delete($request['id']);
    }

    public function findCustomersByQuery(string $query): array
    {
        $customers = [];
        $result = $this->priceOfferCustomerRepository->findCustomersByQuery($query);

        foreach ($result as $customer) {
            $customers[] = PriceOfferCustomerDto::create($customer['id'], $customer['name'], $customer['address'], $customer['city'], $customer['zip']);
        }

        return $customers;
    }
}