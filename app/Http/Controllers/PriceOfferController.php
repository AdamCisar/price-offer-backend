<?php

namespace App\Http\Controllers;

use App\Services\PriceOfferService\PriceOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceOfferController extends Controller
{
    public function __construct(private PriceOfferService $priceOfferService) {}

    public function listPriceOffers(): JsonResponse
    {
        $priceOffers = $this->priceOfferService->getPriceOffers();

        return response()->json($priceOffers, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $item = $this->priceOfferService->createOrUpdate($request->toArray());

        if (!$item) {
            return response()->json(['message' => 'Item not created or updated!'], 404);
        }

        return response()->json($item, 200);
    }

    public function findById(int $id): JsonResponse
    {
        $priceOffer = $this->priceOfferService->getPriceOffer($id);

        if (!$priceOffer) {
            return response()->json(['message' => 'Price offer not found!'], 404);
        }

        return response()->json($priceOffer, 200);
    }

}
