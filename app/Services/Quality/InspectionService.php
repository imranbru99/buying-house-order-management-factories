<?php

namespace App\Services\Quality;

use App\Models\Inspection;
use App\Models\InspectionDefect;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    public const RESULT_PASS = 'pass';

    public const RESULT_FAIL = 'fail';

    /**
     * @param  list<array{defect_code: string, quantity: string|float, severity?: string}>  $defects
     */
    public function record(Inspection $inspection, array $defects, ?int $criticalLimit = 0): Inspection
    {
        return DB::transaction(function () use ($inspection, $defects, $criticalLimit) {
            $criticalCount = 0;
            $majorCount = 0;

            foreach ($defects as $row) {
                $severity = $row['severity'] ?? 'minor';
                InspectionDefect::query()->create([
                    'tenant_id' => $inspection->tenant_id,
                    'inspection_id' => $inspection->id,
                    'defect_code' => $row['defect_code'],
                    'quantity' => $row['quantity'],
                    'severity' => $severity,
                ]);

                $qty = (int) bcadd((string) $row['quantity'], '0', 0);
                if ($severity === 'critical') {
                    $criticalCount += $qty;
                } elseif ($severity === 'major') {
                    $majorCount += $qty;
                }
            }

            $failed = $criticalCount > $criticalLimit || $majorCount > 0;
            $inspection->forceFill([
                'status' => $failed ? self::RESULT_FAIL : self::RESULT_PASS,
            ])->save();

            return $inspection->fresh('defects');
        });
    }
}
