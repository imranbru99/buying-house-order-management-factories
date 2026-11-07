<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Sample\TransitionSampleRequest;
use App\Http\Resources\SampleResource;
use App\Models\Sample;
use App\Services\Samples\SampleWorkflowService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SampleController extends Controller
{
    public function __construct(protected SampleWorkflowService $sampleWorkflowService) {}

    public function index(): AnonymousResourceCollection
    {
        return SampleResource::collection(
            Sample::query()->with(['style', 'buyer', 'factory'])->latest()->paginate()
        );
    }

    public function show(Sample $sample): SampleResource
    {
        return new SampleResource($sample->load(['style', 'buyer', 'factory']));
    }

    public function transition(TransitionSampleRequest $request, Sample $sample): SampleResource
    {
        $data = $request->validated();
        $context = collect($data)->only('factory_id')->filter()->all();

        $sample = $this->sampleWorkflowService->transition($sample, $data['status'], $context);

        return new SampleResource($sample->load(['style', 'buyer', 'factory']));
    }
}
