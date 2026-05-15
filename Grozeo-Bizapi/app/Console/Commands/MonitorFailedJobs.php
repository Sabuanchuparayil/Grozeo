<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonitorFailedJobs extends Command
{
    protected $signature = 'queue:monitor-failed
                            {--hours=24 : Check failed jobs from the last N hours}
                            {--retry : Automatically retry failed jobs}
                            {--prune=168 : Delete failed jobs older than N hours}';

    protected $description = 'Monitor and manage failed queue jobs';

    public function handle(): int
    {
        $hours = $this->option('hours');

        $failedJobs = DB::table('failed_jobs')
            ->where('failed_at', '>=', now()->subHours($hours))
            ->orderByDesc('failed_at')
            ->get();

        if ($failedJobs->isEmpty()) {
            $this->info("No failed jobs in the last {$hours} hours.");
        } else {
            $this->warn("Found {$failedJobs->count()} failed jobs in the last {$hours} hours:");

            $summary = $failedJobs->groupBy(function ($job) {
                $payload = json_decode($job->payload, true);
                return $payload['displayName'] ?? 'Unknown';
            });

            $rows = [];
            foreach ($summary as $jobName => $jobs) {
                $rows[] = [
                    $jobName,
                    $jobs->count(),
                    $jobs->last()->failed_at,
                    str_limit(json_decode($jobs->last()->exception ?? '""', true) ?: $jobs->last()->exception, 80),
                ];
            }

            $this->table(['Job', 'Count', 'Last Failed', 'Error'], $rows);

            Log::warning('Failed jobs summary', [
                'total' => $failedJobs->count(),
                'by_type' => $summary->map->count()->toArray(),
            ]);
        }

        if ($this->option('retry')) {
            $retryCount = 0;
            foreach ($failedJobs as $job) {
                $this->call('queue:retry', ['id' => [$job->uuid ?? $job->id]]);
                $retryCount++;
            }
            $this->info("Retried {$retryCount} failed jobs.");
        }

        $pruneHours = (int) $this->option('prune');
        $pruned = DB::table('failed_jobs')
            ->where('failed_at', '<', now()->subHours($pruneHours))
            ->delete();

        if ($pruned > 0) {
            $this->info("Pruned {$pruned} failed jobs older than {$pruneHours} hours.");
        }

        return self::SUCCESS;
    }
}
