<?php

namespace App\Services\Scrappers;

interface ScrapperInterface {
    public function getItemPrice(array $urls): float;
}
