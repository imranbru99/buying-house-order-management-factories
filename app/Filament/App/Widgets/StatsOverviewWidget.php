<?php

namespace App\Filament\App\Widgets;

use App\Models\Approval;
use App\Models\Buyer;
use App\Models\BuyerPurchaseOrder;
use App\Models\Factory;
use App\Models\FactoryAllocation;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $activeStatuses = ['draft', 'confirmed', 'in_production', 'allocated', 'open', 'approved'];

        $buyers = Buyer::query()->count();
        $factories = Factory::query()->count();
        $activeOrders = BuyerPurchaseOrder::query()
            ->whereIn('status', $activeStatuses)
            ->count();

        $overdueMilestones = Task::query()
            ->whereNotIn('status', ['done', 'completed', 'cancelled'])
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();

        $pendingApprovals = FactoryAllocation::query()
            ->whereIn('status', ['planned', 'pending', 'submitted'])
            ->count()
            + Approval::query()->where('status', 'pending')->count();

        $totalOrderValue = (string) BuyerPurchaseOrder::query()
            ->whereIn('status', $activeStatuses)
            ->sum(DB::raw('COALESCE(total_amount, 0)'));

        return [
            Stat::make('Buyers', (string) $buyers)->icon('heroicon-o-building-storefront'),
            Stat::make('Factories', (string) $factories)->icon('heroicon-o-building-office-2'),
            Stat::make('Active orders', (string) $activeOrders)->icon('heroicon-o-shopping-bag'),
            Stat::make('Overdue milestones', (string) $overdueMilestones)->color('danger')->icon('heroicon-o-clock'),
            Stat::make('Pending approvals', (string) $pendingApprovals)->color('warning')->icon('heroicon-o-hand-thumb-up'),
            Stat::make('Order value (active)', number_format((float) $totalOrderValue, 2))->icon('heroicon-o-banknotes'),
        ];
    }
}
