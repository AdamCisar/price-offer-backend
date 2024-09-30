<?php

namespace App\Traits;

trait ArrayConvertibleTrait
{
    public function toArray(): array
    {
        return (array) $this; // Cast to array
    }
}
