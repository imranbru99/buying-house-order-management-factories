<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\WorkOrder\RejectWorkOrderRequest;
use App\Http\Resources\FactoryWorkOrderResource;
use App\Models\FactoryWorkOrder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WorkOrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return FactoryWorkOrderResource::collection(
            FactoryWorkOrder::query()->with('factory')->latest()->paginate()
        );
    }

    public function show(FactoryWorkOrder $workOrder): FactoryWorkOrderResource
    {
        return new FactoryWorkOrderResource($workOrder->load('factory'));
    }

    public function acknowledge(FactoryWorkOrder $workOrder): FactoryWorkOrderResource
    {
        $workOrder->forceFill([
            'acknowledged_at' => now(),
            'status' => 'acknowledged',
        ])->save();

        return new FactoryWorkOrderResource($workOrder->fresh('factory'));
    }

    public function accept(FactoryWorkOrder $workOrder): FactoryWorkOrderResource
    {
        $workOrder->forceFill([
            'accepted_at' => now(),
            'rejected_at' => null,
            'status' => 'accepted',
        ])->save();

        return new FactoryWorkOrderResource($workOrder->fresh('factory'));
    }

    public function reject(RejectWorkOrderRequest $request, FactoryWorkOrder $workOrder): FactoryWorkOrderResource
    {
        $workOrder->forceFill([
            'rejected_at' => now(),
            'accepted_at' => null,
            'status' => 'rejected',
            'factory_comments' => $request->validated('factory_comments'),
        ])->save();

        return new FactoryWorkOrderResource($workOrder->fresh('factory'));
    }
}
