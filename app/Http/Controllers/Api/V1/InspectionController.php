<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Inspection\StoreInspectionRequest;
use App\Http\Resources\InspectionResource;
use App\Models\Inspection;
use App\Services\Quality\InspectionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InspectionController extends Controller
{
    public function __construct(protected InspectionService $inspectionService) {}

    public function index(): AnonymousResourceCollection
    {
        return InspectionResource::collection(
            Inspection::query()->with('defects')->latest()->paginate()
        );
    }

    public function store(StoreInspectionRequest $request): InspectionResource
    {
        $data = $request->validated();
        $defects = $data['defects'] ?? [];
        $criticalLimit = $data['critical_limit'] ?? 0;

        $inspection = Inspection::query()->create(collect($data)->except(['defects', 'critical_limit'])->all());

        if ($defects !== []) {
            $inspection = $this->inspectionService->record($inspection, $defects, (int) $criticalLimit);
        }

        return new InspectionResource($inspection->load('defects'));
    }
}
