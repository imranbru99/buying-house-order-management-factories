<?php

namespace App\Filament\App\Widgets;

use App\Models\BuyerPurchaseOrder;
use App\Models\FactoryAllocation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AllocationProgressWidget extends ChartWidget
{
    protected static ?string $heading = 'Allocation progress (top orders)';

    protected function getData(): array
    {
        $orders = BuyerPurchaseOrder::query()
            ->select(['id', 'number', 'total_quantity'])
            ->orderByDesc('order_date')
            ->limit(8)
            ->get();

        $labels = [];
        $allocated = [];
        $remaining = [];

        foreach ($orders as $order) {
            $labels[] = $order->number ?? ('PO #'.$order->id);
            $total = (float) ($order->total_quantity ?: 0);
            $sum = (float) FactoryAllocation::query()
                ->where('buyer_purchase_order_id', $order->id)
                ->sum(DB::raw('COALESCE(allocated_quantity, 0)'));
            $allocated[] = round($sum, 2);
            $remaining[] = round(max($total - $sum, 0), 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Allocated',
                    'data' => $allocated,
                    'backgroundColor' => '#0d9488',
                ],
                [
                    'label' => 'Remaining',
                    'data' => $remaining,
                    'backgroundColor' => '#94a3b8',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
