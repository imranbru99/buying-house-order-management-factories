<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Shipment\StoreShipmentRequest;
use App\Http\Resources\ShipmentResource;
use App\Models\BuyerPurchaseOrder;
use App\Models\Shipment;
use App\Services\Shipments\ShipmentService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShipmentController extends Controller
{
    public function __construct(protected ShipmentService $shipmentService) {}

    public function index(): AnonymousResourceCollection
    {
        return ShipmentResource::collection(
            Shipment::query()->with(['items', 'purchaseOrder'])->latest()->paginate()
        );
    }

    public function store(StoreShipmentRequest $request): ShipmentResource
    {
        $data = $request->validated();
        $order = BuyerPurchaseOrder::query()->findOrFail($data['buyer_purchase_order_id']);

        $shipment = $this->shipmentService->create(
            $order,
            collect($data)->except(['buyer_purchase_order_id', 'items', 'allow_overship'])->all(),
            $data['items'],
            (bool) ($data['allow_overship'] ?? false),
        );

        return new ShipmentResource($shipment->load(['items', 'purchaseOrder']));
    }

    public function show(Shipment $shipment): ShipmentResource
    {
        return new ShipmentResource($shipment->load(['items', 'purchaseOrder']));
    }
}
