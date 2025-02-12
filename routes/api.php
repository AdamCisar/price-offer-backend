<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PriceOfferController;
use App\Http\Controllers\UserController;
use App\Services\Scrappers\ScrapperService;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Route::get('/', [ScrapperService::class, 'importPrices']);

    /** USERS */
    Route::group(['prefix' => 'user'], function () {
        Route::post('/', [UserController::class, 'update']);
        Route::get('/', [UserController::class, 'show']);
    });

    /** PRICE OFFERS */
    Route::group(['prefix' => 'price-offers'], function () {
        Route::get('/', [PriceOfferController::class, 'index']);
        Route::get('/{id}', [PriceOfferController::class, 'show']);
        Route::post('/', [PriceOfferController::class, 'save']);
        Route::post('/details', [PriceOfferController::class, 'updatePriceOfferDetails']);
        Route::delete('/', [PriceOfferController::class, 'destroy']);
        Route::get('customers/search/{query}', [PriceOfferController::class, 'findCustomersByQuery']);
    });

    /** ITEMS */
    Route::group(['prefix' => 'items'], function () {
        Route::get('/search/{query}', [ItemController::class, 'findByQuery']);
        Route::post('/', [ItemController::class, 'save']);
        Route::get('/', [ItemController::class, 'index']);
        Route::delete('/', [ItemController::class, 'destroy']);
    });
});
