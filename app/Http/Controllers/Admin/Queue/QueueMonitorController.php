<?php

namespace App\Http\Controllers\Admin\Queue;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class QueueMonitorController extends Controller
{
    public function index()
    {
        return view('Admin.Queue.monitor');
    }

    /**
     * GET /api/queue/stats
     * Summary metric cards.
     */
    public function stats(): JsonResponse
    {
        $pending = DB::table('jobs')->count();

        $pendingByQueue = DB::table('jobs')
            ->select('queue', DB::raw('count(*) as count'))
            ->groupBy('queue')->pluck('count', 'queue');

        $failed24h = DB::table('failed_jobs')
            ->where('failed_at', '>=', now()->subDay())
            ->count();

        $failedTotal = DB::table('failed_jobs')->count();

        $oldestJob   = DB::table('jobs')->orderBy('created_at')->first();
        $avgWaitSecs = $oldestJob ? now()->diffInSeconds(Carbon::createFromTimestamp($oldestJob->created_at)) : 0;

        $processedToday = (int) Cache::get('queue_monitor:processed_today', 0);

        $jobsPerMinute  = (int) Cache::get('queue_monitor:jobs_per_minute', 0);

        $processing = DB::table('jobs')->whereNotNull('reserved_at')->count();

        return response()->json([
            'jobs_per_minute'   => $jobsPerMinute,
            'pending'           => $pending,
            'processing'        => $processing,
            'failed_24h'        => $failed24h,
            'failed_total'      => $failedTotal,
            'avg_wait_seconds'  => round($avgWaitSecs),
            'processed_today'   => $processedToday,
            'pending_by_queue'  => $pendingByQueue,
        ]);
    }

    /**
     * GET /api/queue/queues
     * Per-queue breakdown.
     */
    public function queues(): JsonResponse
    {
        $rows = DB::table('jobs')
            ->select(
                'queue',
                DB::raw('count(*) as total'),
                DB::raw('sum(case when reserved_at is not null then 1 else 0 end) as processing'),
                DB::raw('sum(case when reserved_at is null then 1 else 0 end) as pending'),
                DB::raw('min(created_at) as oldest_created_at'),
                DB::raw('max(attempts) as max_attempts'),
                DB::raw('avg(attempts) as avg_attempts')
            )->groupBy('queue')->get();

        $failedCounts = DB::table('failed_jobs')
            ->select('queue', DB::raw('count(*) as count'))
            ->groupBy('queue')->pluck('count', 'queue');

        $data = $rows->map(function ($row) use ($failedCounts) {
            $waitSecs  = $row->oldest_created_at ? now()->diffInSeconds(Carbon::createFromTimestamp($row->oldest_created_at)) : 0;

            $load = $row->total > 0 ? min(100, (int) round(($row->processing / max(1, $row->total)) * 100)) : 0;

            return [
                'name'         => $row->queue,
                'connection'   => config('queue.default', 'database'),
                'pending'      => (int) $row->pending,
                'processing'   => (int) $row->processing,
                'total'        => (int) $row->total,
                'failed'       => (int) ($failedCounts[$row->queue] ?? 0),
                'max_attempts' => (int) $row->max_attempts,
                'avg_attempts' => round($row->avg_attempts, 1),
                'wait_seconds' => round($waitSecs),
                'load'         => $load,
                'status'       => $this->resolveQueueStatus((int) $row->pending, (int) $row->processing),
            ];
        });

        return response()->json($data->values());
    }

    /**
     * GET /api/queue/pending
     * Paginated list of pending jobs with decoded payload.
     * 
     */
    public function pending(): JsonResponse
    {
        $jobs = DB::table('jobs')
            ->orderBy('created_at')
            ->limit(50)
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true) ?? [];
                return [
                    'id'          => $job->id,
                    'queue'       => $job->queue,
                    'job'         => $this->parseJobName($payload),
                    'attempts'    => (int) $job->attempts,
                    'reserved_at' => $job->reserved_at ? Carbon::createFromTimestamp($job->reserved_at)->diffForHumans() : null,
                    'created_at'  => Carbon::createFromTimestamp($job->created_at)->diffForHumans(),
                    'status'      => $job->reserved_at ? 'processing' : 'pending',
                    'payload_preview' => $this->safePayloadPreview($payload),
                ];
            });

        return response()->json($jobs);
    }

    /**
     * GET /api/queue/failed
     * Recently failed jobs.
     */
    public function failed(): JsonResponse
    {
        $jobs = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->limit(50)
            ->get()
            ->map(function ($job) {
                $payload   = json_decode($job->payload, true) ?? [];
                $exception = $job->exception ?? '';

                $shortError = collect(explode("\n", $exception))
                    ->map(fn($l) => trim($l))->filter()
                    ->first() ?? 'Unknown error';

                return [
                    'id'         => $job->uuid,
                    'job'        => $this->parseJobName($payload),
                    'queue'      => $job->queue,
                    'connection' => $job->connection,
                    'failed_at'  => Carbon::parse($job->failed_at)->diffForHumans(),
                    'failed_at_absolute' => Carbon::parse($job->failed_at)->toDateTimeString(),
                    'attempts'   => (int) ($payload['attempts'] ?? 1),
                    'error'      => $shortError,
                    'exception'  => $exception,
                ];
            });

        return response()->json($jobs);
    }

    /**
     * GET /api/queue/throughput
     * Last 12 one-minute buckets for the sparkline chart.
     * Reads from cache keys written by QueueMonitorListener.
     */
    public function throughput(): JsonResponse
    {
        $points = collect(range(11, 0))->map(function ($minutesAgo) {
            $key   = 'queue_monitor:throughput:' . now()->subMinutes($minutesAgo)->format('YmdHi');
            $value = (int) Cache::get($key, 0);
            return [
                'label' => '-' . $minutesAgo . 'm',
                'value' => $value,
            ];
        });

        return response()->json($points);
    }

    /**
     * POST /api/queue/failed/{uuid}/retry
     * Re-queues a failed job.
     */
    public function retry(string $uuid): JsonResponse
    {
        $job = DB::table('failed_jobs')->where('uuid', $uuid)->first();

        if (! $job) {
            return response()->json(['success' => false, 'message' => 'Job not found.'], 404);
        }

        try {
            $payload = json_decode($job->payload, true);
            $delay   = 0;

            DB::table($job->queue === 'default' ? 'jobs' : 'jobs')
                ->insert([
                    'queue'      => $job->queue,
                    'payload'    => $job->payload,
                    'attempts'   => 0,
                    'reserved_at' => null,
                    'available_at' => now()->addSeconds($delay)->timestamp,
                    'created_at' => now()->timestamp,
                ]);

            DB::table('failed_jobs')->where('uuid', $uuid)->delete();

            return response()->json(['success' => true, 'message' => 'Job re-queued successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/queue/failed/{uuid}
     * Permanently removes a failed job record.
     */
    public function forget(string $uuid): JsonResponse
    {
        $deleted = DB::table('failed_jobs')->where('uuid', $uuid)->delete();

        return $deleted ? response()->json(['success' => true, 'message' => 'Job removed.']) : response()->json(['success' => false, 'message' => 'Job not found.'], 404);
    }

    /**
     * DELETE /api/queue/failed
     * Clears ALL failed jobs.
     */
    public function forgetAll(): JsonResponse
    {
        $count = DB::table('failed_jobs')->count();
        DB::table('failed_jobs')->truncate();

        return response()->json(['success' => true, 'deleted' => $count]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function resolveQueueStatus(int $pending, int $processing): string
    {
        if ($processing > 0 && $pending === 0) return 'processing';
        if ($pending === 0 && $processing === 0) return 'idle';
        if ($pending > 50) return 'busy';
        return 'active';
    }

    private function parseJobName(array $payload): string
    {
        $class = $payload['displayName'] ?? $payload['job'] ?? 'Unknown';
        return class_basename($class);
    }

    private function safePayloadPreview(array $payload): array
    {
        $data = $payload['data'] ?? $payload['command'] ?? [];
        if (is_string($data)) {
            try {
                $obj  = unserialize($data);
                $data = (array) $obj;
            } catch (\Throwable) {
                $data = ['raw' => substr($data, 0, 100)];
            }
        }
        return collect($data)
            ->filter(fn($v) => is_scalar($v))
            ->take(5)->toArray();
    }
}
