<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryMonitor
{
    private const QUERY_THRESHOLD = 50;
    private const DUPLICATE_THRESHOLD = 5;

    public function handle(Request $request, Closure $next)
    {
        if (!app()->environment('production')) {
            DB::enableQueryLog();
        }

        $response = $next($request);

        if (!app()->environment('production')) {
            $queries = DB::getQueryLog();
            $queryCount = count($queries);

            if ($queryCount > self::QUERY_THRESHOLD) {
                Log::warning('High query count detected (possible N+1)', [
                    'query_count' => $queryCount,
                    'path' => $request->path(),
                    'threshold' => self::QUERY_THRESHOLD,
                ]);
            }

            $duplicates = $this->findDuplicateQueries($queries);
            if (!empty($duplicates)) {
                Log::warning('Duplicate queries detected (likely N+1)', [
                    'path' => $request->path(),
                    'duplicates' => $duplicates,
                ]);
            }

            DB::disableQueryLog();
        }

        return $response;
    }

    private function findDuplicateQueries(array $queries): array
    {
        $seen = [];
        foreach ($queries as $query) {
            $normalized = preg_replace('/\b\d+\b/', '?', $query['query']);
            $seen[$normalized] = ($seen[$normalized] ?? 0) + 1;
        }

        return array_filter($seen, fn($count) => $count >= self::DUPLICATE_THRESHOLD);
    }
}
