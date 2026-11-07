<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Orders\ApprovePurchaseOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\StoreOrderRequest;
use App\Http\Requests\Api\V1\Order\UpdateOrderRequest;
use App\Http\Resources\BuyerPurchaseOrderResource;
use App\Models\BuyerPurchaseOrder;
use App\Services\Orders\PurchaseOrderService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        protected PurchaseOrderService $purchaseOrderService,
        protected ApprovePurchaseOrder $approvePurchaseOrder,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return BuyerPurchaseOrderResource::collection(
            BuyerPurchaseOrder::query()->with('buyer', 'items')->latest()->paginate()
        );
    }

    public function store(StoreOrderRequest $request): BuyerPurchaseOrderResource
    {
        $data = $request->validated();
        $order = $this->purchaseOrderService->create(
            collect($data)->except('items')->all(),
            $data['items'] ?? []
        );

        return new BuyerPurchaseOrderResource($order->load('buyer', 'items'));
    }

    public function show(BuyerPurchaseOrder $order): BuyerPurchaseOrderResource
    {
        return new BuyerPurchaseOrderResource($order->load('buyer', 'items'));
    }

    public function update(UpdateOrderRequest $request, BuyerPurchaseOrder $order): BuyerPurchaseOrderResource
    {
        $order->update($request->validated());

        return new BuyerPurchaseOrderResource($order->fresh('buyer', 'items'));
    }

    public function destroy(BuyerPurchaseOrder $order): \Illuminate\Http\JsonResponse
    {
        $order->delete();

        return response()->json(null, 204);
    }

    public function approve(BuyerPurchaseOrder $order): BuyerPurchaseOrderResource
    {
        return new BuyerPurchaseOrderResource(
            $this->approvePurchaseOrder->execute($order)->load('buyer', 'items')
        );
    }

    public function confirm(BuyerPurchaseOrder $order): BuyerPurchaseOrderResource
    {
        return new BuyerPurchaseOrderResource(
            $this->purchaseOrderService->confirm($order)->load('buyer', 'items')
        );
    }

    public function cancel(BuyerPurchaseOrder $order): BuyerPurchaseOrderResource
    {
        return new BuyerPurchaseOrderResource(
            $this->purchaseOrderService->cancel($order)->load('buyer', 'items')
        );
    }
}
