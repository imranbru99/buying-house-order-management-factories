<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Allocations\AllocateOrderToFactories;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Allocation\StoreAllocationRequest;
use App\Http\Resources\FactoryAllocationResource;
use App\Models\BuyerPurchaseOrder;
use App\Models\FactoryAllocation;
use App\Services\Allocation\FactoryAllocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AllocationController extends Controller
{
    public function __construct(
        protected FactoryAllocationService $allocationService,
        protected AllocateOrderToFactories $allocateOrderToFactories,
    ) {}

    public function index(BuyerPurchaseOrder $order): AnonymousResourceCollection
    {
        $allocations = $order->allocations()->with('factory')->get();

        return FactoryAllocationResource::collection($allocations);
    }

    public function store(StoreAllocationRequest $request, BuyerPurchaseOrder $order): JsonResponse
    {
        $created = $this->allocateOrderToFactories->execute($order, $request->validated('allocations'));

        $ids = collect($created)->pluck('id');
        $loaded = FactoryAllocation::query()->with('factory')->whereIn('id', $ids)->get();

        return response()->json([
            'data' => FactoryAllocationResource::collection($loaded),
            'unallocated_quantity' => $this->allocationService->unallocatedQuantity($order),
        ], 201);
    }

    public function approve(FactoryAllocation $allocation): FactoryAllocationResource
    {
        $this->allocationService->approve($allocation);

        return new FactoryAllocationResource($allocation->fresh('factory', 'workOrders'));
    }
}
