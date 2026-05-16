<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Cache;

class QueueMonitorListener
{
    public function handleProcessing(JobProcessing $event): void
    {
    }

    public function handleProcessed(JobProcessed $event): void
    {
        $this->incrementCounters();
    }

    public function handleFailed(JobFailed $event): void
    {
        Cache::increment('queue_monitor:failed_today');
    }

    private function incrementCounters(): void
    {
        $bucketKey = 'queue_monitor:throughput:' . now()->format('YmdHi');
        Cache::increment($bucketKey);
        Cache::put($bucketKey, Cache::get($bucketKey, 1), now()->addMinutes(15));
        $windowKey = 'queue_monitor:window:' . now()->format('YmdHis');
        Cache::put($windowKey, 1, now()->addSeconds(60));
        Cache::put(
            'queue_monitor:jobs_per_minute',
            (int) Cache::get('queue_monitor:throughput:' . now()->format('YmdHi'), 0),
            now()->addSeconds(90)
        );

        Cache::increment('queue_monitor:processed_today');
        if (now()->hour === 0 && now()->minute === 0) {
            Cache::put('queue_monitor:processed_today', 0, now()->endOfDay());
        }
    }
}