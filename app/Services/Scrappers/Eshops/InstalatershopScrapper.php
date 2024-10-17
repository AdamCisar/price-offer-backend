<?php

namespace App\Services\Scrappers\Eshops;

use App\Services\Scrappers\ScrapperInterface;
use Symfony\Component\DomCrawler\Crawler;

class InstalatershopScrapper implements ScrapperInterface
{
    public function __construct(private \GuzzleHttp\Client $guzzle) {}
    
    public function getItemPrice(array $urls): float
    {
        $price = 0;

        if (empty($urls['instalatershop'])) {
            return $price;
        }

        $response = $this->guzzle->request('GET', $urls['instalatershop']);

        $html = $response->getBody();
        $crawler = new Crawler($html);

        $price = (float) str_replace(',','.',$crawler->filter('div.p-final-price-wrapper')->filter('span.price-additional')->first()->text());

        return $price;
    }
}