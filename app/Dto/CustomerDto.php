<?php

namespace App\Dto;

use App\Traits\ArrayConvertibleTrait;

class CustomerDto
{
    use ArrayConvertibleTrait;
    
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $address,
        public readonly string $city,
        public readonly string $zip,
    ) {}

    public static function create(
            int $id, 
            string $name, 
            string $address,
            string $city,
            string $zip
        ): CustomerDto 
    {
        return new self(
            $id, 
            $name, 
            $address, 
            $city, 
            $zip
        );
    }
}
