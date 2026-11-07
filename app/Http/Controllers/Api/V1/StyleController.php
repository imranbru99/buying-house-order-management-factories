<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Style\StoreStyleRequest;
use App\Http\Requests\Api\V1\Style\UpdateStyleRequest;
use App\Http\Resources\StyleResource;
use App\Models\Style;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StyleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return StyleResource::collection(
            Style::query()->with('buyer')->latest()->paginate()
        );
    }

    public function store(StoreStyleRequest $request): StyleResource
    {
        $style = Style::query()->create($request->validated());

        return new StyleResource($style->load('buyer'));
    }

    public function show(Style $style): StyleResource
    {
        return new StyleResource($style->load('buyer'));
    }

    public function update(UpdateStyleRequest $request, Style $style): StyleResource
    {
        $style->update($request->validated());

        return new StyleResource($style->fresh('buyer'));
    }
}
