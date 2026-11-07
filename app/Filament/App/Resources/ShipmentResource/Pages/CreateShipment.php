<?php

namespace App\Filament\App\Resources\ShipmentResource\Pages;

use App\Filament\App\Resources\ShipmentResource;
use App\Models\BuyerPurchaseOrder;
use App\Models\Shipment;
use App\Services\Shipments\ShipmentService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $order = BuyerPurchaseOrder::query()->findOrFail($data['buyer_purchase_order_id']);
        $items = $data['items'] ?? [];

        $header = [
            'number' => $data['number'],
            'status' => $data['status'] ?? 'planned',
            'incoterm' => $data['incoterm'] ?? null,
            'shipping_method' => $data['shipping_method'] ?? null,
            'etd' => $data['etd'] ?? null,
            'eta' => $data['eta'] ?? null,
            'bol_number' => $data['bol_number'] ?? null,
        ];

        try {
            return app(ShipmentService::class)->create($order, $header, $items);
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();

            $this->halt();

            return new Shipment;
        }
    }
}
