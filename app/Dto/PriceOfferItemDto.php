<?php

namespace App\Dto;

use App\Traits\ArrayConvertibleTrait;

class PriceOfferItemDto
{
    use ArrayConvertibleTrait;

    private function __construct(
        public readonly int $id,
        public readonly int $item_id,
        public readonly string $title,
        public readonly string $unit,
        public readonly float $price,
        public readonly float $quantity,
        public readonly float $total,
        public readonly int $ordering
    ) {}

    public static function create(
            int $id, 
            int $item_id,
            string $title,
            string $unit,
            float $price,
            float $quantity,
            float $total,
            int $ordering
        ): PriceOfferItemDto 
    {
        return new self(
            $id, 
            $item_id,
            $title,
            $unit,
            $price,
            $quantity,
            $total,
            $ordering
        );
    }

}
