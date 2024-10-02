<?php

namespace App\Http\Controllers;

use App\Services\PriceOfferService\PriceOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceOfferController extends Controller
{
    public function __construct(private PriceOfferService $priceOfferService) {}

    public function index(): JsonResponse
    {
        $priceOffers = $this->priceOfferService->getPriceOffers();

        return response()->json($priceOffers, 200);
    }

    public function show(int $id): JsonResponse
    {
        $priceOffer = $this->priceOfferService->getPriceOffer($id);

        if (!$priceOffer) {
            return response()->json(['message' => 'Price offer not found!'], 404);
        }

        return response()->json($priceOffer, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $priceOffer = $this->priceOfferService->createOrUpdate($request->toArray());

        if (!$priceOffer) {
            return response()->json(['message' => 'Price offer has not been created or updated!'], 400);
        }

        return response()->json($priceOffer, 200);
    }

    public function destroy(Request $request): JsonResponse
    {
        $isDeleted = $this->priceOfferService->delete($request->toArray());

        if (!$isDeleted) {
            return response()->json(['message' => 'Price offer has not been deleted!'], 400);
        }

        return response()->json(['message' => 'Price offer has been deleted!'], 200);
    }                      
}
