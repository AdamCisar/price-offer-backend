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
        $request = $request->all();
        
        if (empty($request)) {
            return response()->json(['message' => 'Price offer has not been created!'], 400);
        };

        $priceOffer = $this->priceOfferService->createOrUpdate($request);

        if (!$priceOffer) {
            return response()->json(['message' => 'Price offer has not been created or updated!'], 400);
        }

        return response()->json($priceOffer, 200);
    }

    public function updatePriceOfferDetails(Request $request): JsonResponse
    {
        $request = $request->all();
        
        if (empty($request)) {
            return response()->json(['message' => 'Price offer details has not been updated!'], 400);
        };

        $priceOffer = $this->priceOfferService->updatePriceOfferDetails($request);

        if (!$priceOffer) {
            return response()->json(['message' => 'Price offer details has not been updated!'], 400);
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
    
    public function findCustomersByQuery(string $query): JsonResponse
    {
        $customers = $this->priceOfferService->findCustomersByQuery($query);

        return response()->json($customers, 200);
    }
}
