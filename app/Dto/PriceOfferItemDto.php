<?php

namespace App\Dto;

use App\Traits\ArrayConvertibleTrait;

class PriceOfferItemDto
{
    use ArrayConvertibleTrait;

    private function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $unit,
        public readonly float $price,
        public readonly float $quantity,
        public readonly float $total,
    ) {}

    public static function create(
            int $id, 
            string $title,
            string $unit,
            float $price,
            float $quantity,
            float $total
        ): PriceOfferItemDto 
    {
        return new self(
            $id, 
            $title,
            $unit,
            $price,
            $quantity,
            $total
        );
    }

}
