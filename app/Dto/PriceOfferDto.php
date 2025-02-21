<?php

namespace App\Dto;

use App\Traits\ArrayConvertibleTrait;

class PriceOfferDto
{
    use ArrayConvertibleTrait;
    
    private function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly float $total,
        public readonly bool $is_vat,
        public readonly string $notes,
        public readonly PriceOfferCustomerDto $customer,
        public readonly array $items,
    ) {}

     /**
     * Creates a PriceOfferDto instance.
     * 
     * @param int $id The ID of the price offer.
     * @param PriceOfferCustomerDto $customer The associated customer.
     * @param PriceOfferItemDto[] $items An array of ItemDto instances.
     * @return PriceOfferDto The created PriceOfferDto instance.
     * @throws \InvalidArgumentException If any item is not an instance of ItemDto.
     */
    public static function create(int $id, string $title, string $description, float $total, bool $is_vat, $notes, PriceOfferCustomerDto $customer, array $items): PriceOfferDto 
    {
        foreach ($items as $item) {
            if (!$item instanceof PriceOfferItemDto) {
                throw new \InvalidArgumentException('Each item must be an instance of ItemDto.');
            }
        }
        
        return new self($id, $title, $description, $total, $is_vat, $notes, $customer, $items);
    }

}
