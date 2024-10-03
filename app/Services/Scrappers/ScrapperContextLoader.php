<?php

namespace App\Services\Scrappers;

use GuzzleHttp\Client;

class ScrapperContextLoader
{
    private Client $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
        $this->loadScrappers();
    }

    public function loadScrappers()
    {
        $scrappers = [];
        $classes = $this->getScrapperClasses();

        foreach ($classes as $file) {
            $className = basename($file, '.php');

            $fullClassName = "App\\Services\\Scrappers\\Eshops\\" . $className;

            if (class_exists($fullClassName)) {
                $scrappers[] = new $fullClassName($this->guzzle);
            }
        }

        return $scrappers;
    }

    public function getScrapperClasses(): array
    {
        $folderPath = __DIR__ . '/Eshops/';
        $classes = glob($folderPath . '*.php');

        if (!$classes) {
            return [];
        }

        return $classes;
    }
}
