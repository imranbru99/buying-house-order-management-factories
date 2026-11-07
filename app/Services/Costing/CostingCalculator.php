<?php

namespace App\Services\Costing;

use App\Models\CostingItem;
use App\Models\CostingSheet;
use Illuminate\Support\Facades\DB;

class CostingCalculator
{
    private const SCALE = 4;

    public function recalculate(CostingSheet $sheet): CostingSheet
    {
        return DB::transaction(function () use ($sheet) {
            $sheet->load('items');
            $total = '0.0000';

            foreach ($sheet->items as $item) {
                $lineTotal = $this->multiply((string) $item->quantity, (string) $item->unit_cost);
                $item->forceFill(['line_total' => $lineTotal])->save();
                $total = $this->add($total, $lineTotal);
            }

            $sheet->forceFill(['total_cost' => $total])->save();

            return $sheet->fresh('items');
        });
    }

    public function recalculateItem(CostingItem $item): CostingItem
    {
        $lineTotal = $this->multiply((string) $item->quantity, (string) $item->unit_cost);
        $item->forceFill(['line_total' => $lineTotal])->save();

        if ($item->costing_sheet_id) {
            $this->recalculate($item->sheet);
        }

        return $item;
    }

    public function multiply(string $left, string $right): string
    {
        return bcadd(bcmul($this->normalize($left), $this->normalize($right), self::SCALE), '0', self::SCALE);
    }

    public function add(string $left, string $right): string
    {
        return bcadd($this->normalize($left), $this->normalize($right), self::SCALE);
    }

    protected function normalize(string $value): string
    {
        return $value === '' ? '0' : $value;
    }
}
