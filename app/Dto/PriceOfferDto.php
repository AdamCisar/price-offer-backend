<?php

namespace App\Dto;

use App\Traits\ArrayConvertibleTrait;

class PriceOfferDto
{
    use ArrayConvertibleTrait;
    
    private function __construct(
        public readonly int $id,
        public readonly float $total,
        public readonly CustomerDto $customer,
        public readonly array $items,
    ) {}

     /**
     * Creates a PriceOfferDto instance.
     * 
     * @param int $id The ID of the price offer.
     * @param CustomerDto $customer The associated customer.
     * @param ItemDto[] $items An array of ItemDto instances.
     * @return PriceOfferDto The created PriceOfferDto instance.
     * @throws \InvalidArgumentException If any item is not an instance of ItemDto.
     */
    public static function create(int $id, float $total, CustomerDto $customer, array $items): PriceOfferDto 
    {
        foreach ($items as $item) {
            if (!$item instanceof ItemDto) {
                throw new \InvalidArgumentException('Each item must be an instance of ItemDto.');
            }
        }
        
        return new self($id, $total, $customer, $items);
    }

}
