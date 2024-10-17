<?php

namespace App\Services\Scrappers\Eshops;

use App\Services\Scrappers\ScrapperInterface;
use Symfony\Component\DomCrawler\Crawler;

class EmpiriaScrapper implements ScrapperInterface
{
    public function __construct(private \GuzzleHttp\Client $guzzle) {}
    
    public function getItemPrice(array $urls): float
    {
        $price = 0;

        if (empty($urls['empiria'])) {
            return $price;
        }

        $response = $this->guzzle->request('GET', $urls['empiria']);

        $html = $response->getBody();
        $crawler = new Crawler($html);

        $price = (float) str_replace(',','.',$crawler->filter('div[class="card__price"]')->first()->text());
        
        return $price;
    }
}