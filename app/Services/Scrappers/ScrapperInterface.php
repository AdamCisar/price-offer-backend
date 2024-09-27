<?php

namespace App\Services\Scrappers;

interface ScrapperInterface {
    public function getItemPrice(string $url): float;
}
