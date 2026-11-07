<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Buyer\StoreBuyerRequest;
use App\Http\Requests\Api\V1\Buyer\UpdateBuyerRequest;
use App\Http\Resources\BuyerResource;
use App\Models\Buyer;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BuyerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BuyerResource::collection(Buyer::query()->latest()->paginate());
    }

    public function store(StoreBuyerRequest $request): BuyerResource
    {
        $buyer = Buyer::query()->create($request->validated());

        return new BuyerResource($buyer);
    }

    public function show(Buyer $buyer): BuyerResource
    {
        return new BuyerResource($buyer);
    }

    public function update(UpdateBuyerRequest $request, Buyer $buyer): BuyerResource
    {
        $buyer->update($request->validated());

        return new BuyerResource($buyer);
    }

    public function destroy(Buyer $buyer): \Illuminate\Http\JsonResponse
    {
        $buyer->delete();

        return response()->json(null, 204);
    }
}
