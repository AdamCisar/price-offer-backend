<?php

use Illuminate\Support\Facades\Route;
use App\Services\Scrappers\Scrapper;

Route::get('/', [Scrapper::class, 'importPrices']);
