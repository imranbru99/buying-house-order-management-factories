<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Factory\StoreFactoryRequest;
use App\Http\Requests\Api\V1\Factory\UpdateFactoryRequest;
use App\Http\Resources\FactoryResource;
use App\Models\Factory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FactoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return FactoryResource::collection(Factory::query()->latest()->paginate());
    }

    public function store(StoreFactoryRequest $request): FactoryResource
    {
        $factory = Factory::query()->create($request->validated());

        return new FactoryResource($factory);
    }

    public function show(Factory $factory): FactoryResource
    {
        return new FactoryResource($factory);
    }

    public function update(UpdateFactoryRequest $request, Factory $factory): FactoryResource
    {
        $factory->update($request->validated());

        return new FactoryResource($factory);
    }

    public function destroy(Factory $factory): \Illuminate\Http\JsonResponse
    {
        $factory->delete();

        return response()->json(null, 204);
    }
}
