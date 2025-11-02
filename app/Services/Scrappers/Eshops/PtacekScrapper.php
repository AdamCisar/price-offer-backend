<?php

namespace App\Services\Scrappers\Eshops;

use App\Exceptions\PtacekScrapperException;
use App\Services\Scrappers\ScrapperInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Cookie\CookieJar;

class PtacekScrapper implements ScrapperInterface
{
    public function __construct(
        private Client $guzzle, 
        private ?CookieJar $jar = null,
        private ?string $email = null,
        private ?string $password = null,
    ) {}
    
    public function getItemPrice(array $urls): float
    {
        $price = 0;
        $url = data_get(collect($urls)->firstWhere('shop', 'ptacek'), 'url', '');

        if (empty($url)) {
            return $price;
        }

        $response = $this->guzzle->request('GET', $url, ['cookies' => $this->jar]);

        $html = $response->getBody();
        $crawler = new Crawler($html);

        if (str_contains($crawler->filter('title')->first()->text(), 'Prihlásenie')) {
            $viewState = $crawler->filter('input[name="__VIEWSTATE"]')->attr('value');
            $viewStateGen = $crawler->filter('input[name="__VIEWSTATEGENERATOR"]')->attr('value');
            $eventValidation = $crawler->filter('input[name="__EVENTVALIDATION"]')->attr('value');

            $response = $this->guzzle->post('https://eshop.ptacek.sk/prihlaseni', [
            'form_params' => [
                    'login' => $this->email,
                    'password' => $this->password,
                    'send' => 'true',
                    '__VIEWSTATE' => $viewState,
                    '__VIEWSTATEGENERATOR' => $viewStateGen,
                    '__EVENTVALIDATION' => $eventValidation,
                ],
                'cookies' => $this->jar,
                'allow_redirects' => true,
            ]);

            if (str_contains(
                (new Crawler($response->getBody()->getContents()))->filter('title')->first()->text(), 'Prihlásenie')
            ) {
                throw PtacekScrapperException::invalidCredentials();
            }

            return (new self($this->guzzle, $this->jar))->getItemPrice($urls);
        }

        $price = (float) str_replace(
            ',',
            '.',
            $crawler->filter('#ctl00_ctl00_BaseContentRoot_MainContent_CustPrice')->first()->text()
        );
        
        return $price;
    }
}