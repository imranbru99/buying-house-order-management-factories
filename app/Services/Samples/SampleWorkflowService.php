<?php

namespace App\Services\Samples;

use App\Events\SampleStatusChanged;
use App\Models\Sample;
use App\Services\Audit\AuditLogger;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SampleWorkflowService
{
    /** @var array<string, list<string>> */
    protected array $transitions = [
        'requested' => ['assigned'],
        'assigned' => ['in_development'],
        'in_development' => ['submitted'],
        'submitted' => ['buyer_review'],
        'buyer_review' => ['approved', 'rejected'],
        'approved' => ['completed'],
        'rejected' => ['in_development'],
    ];

    public function __construct(protected AuditLogger $auditLogger) {}

    public function transition(Sample $sample, string $toStatus, array $context = []): Sample
    {
        $from = $sample->status;
        $allowed = $this->transitions[$from] ?? [];

        if (! in_array($toStatus, $allowed, true)) {
            throw new InvalidArgumentException("Invalid sample transition from [{$from}] to [{$toStatus}].");
        }

        return DB::transaction(function () use ($sample, $from, $toStatus, $context) {
            if ($toStatus === 'assigned' && isset($context['factory_id'])) {
                $sample->factory_id = $context['factory_id'];
            }

            $sample->forceFill(['status' => $toStatus])->save();
            $this->auditLogger->log('sample.status_changed', $sample, ['status' => $from], ['status' => $toStatus]);
            event(new SampleStatusChanged($sample, $from, $toStatus));

            return $sample->fresh();
        });
    }
}
