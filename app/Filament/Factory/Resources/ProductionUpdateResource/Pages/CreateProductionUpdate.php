<?php

namespace App\Filament\Factory\Resources\ProductionUpdateResource\Pages;

use App\Filament\Factory\Resources\ProductionUpdateResource;
use App\Models\ProductionPlan;
use App\Models\ProductionUpdate;
use App\Services\Production\ProductionService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CreateProductionUpdate extends CreateRecord
{
    protected static string $resource = ProductionUpdateResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $plan = ProductionPlan::query()->findOrFail($data['production_plan_id']);

        try {
            return app(ProductionService::class)->recordUpdate(
                $plan,
                (string) $data['completed_quantity'],
                $data['notes'] ?? null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();

            $this->halt();

            return new ProductionUpdate;
        }
    }
}
