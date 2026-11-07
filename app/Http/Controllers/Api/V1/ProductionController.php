<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Production\StoreProductionUpdateRequest;
use App\Http\Resources\ProductionUpdateResource;
use App\Models\ProductionPlan;
use App\Models\ProductionUpdate;
use App\Services\Production\ProductionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductionController extends Controller
{
    public function __construct(protected ProductionService $productionService) {}

    public function index(): AnonymousResourceCollection
    {
        return ProductionUpdateResource::collection(
            ProductionUpdate::query()->latest()->paginate()
        );
    }

    public function store(StoreProductionUpdateRequest $request): ProductionUpdateResource
    {
        $data = $request->validated();
        $plan = ProductionPlan::query()->findOrFail($data['production_plan_id']);

        $update = $this->productionService->recordUpdate(
            $plan,
            (string) $data['completed_quantity'],
            $data['notes'] ?? null,
        );

        return new ProductionUpdateResource($update);
    }
}
