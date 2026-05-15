<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        $checks = [
            'status' => 'healthy',
            'service' => 'grozeo-bizapi',
            'timestamp' => now()->toIso8601String(),
            'checks' => [],
        ];

        $checks['checks']['database'] = $this->checkDatabase();
        $checks['checks']['redis'] = $this->checkRedis();
        $checks['checks']['cache'] = $this->checkCache();

        $allHealthy = collect($checks['checks'])->every(fn($c) => $c['status'] === 'up');
        $checks['status'] = $allHealthy ? 'healthy' : 'degraded';

        return response()->json($checks, $allHealthy ? 200 : 503);
    }

    public function ping(): JsonResponse
    {
        return response()->json(['status' => 'ok', 'service' => 'grozeo-bizapi']);
    }

    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $latency = round((microtime(true) - $start) * 1000, 2);
            return ['status' => 'up', 'latency_ms' => $latency];
        } catch (\Throwable $e) {
            return ['status' => 'down', 'error' => 'Connection failed'];
        }
    }

    private function checkRedis(): array
    {
        try {
            $start = microtime(true);
            Redis::ping();
            $latency = round((microtime(true) - $start) * 1000, 2);
            return ['status' => 'up', 'latency_ms' => $latency];
        } catch (\Throwable $e) {
            return ['status' => 'down', 'error' => 'Connection failed'];
        }
    }

    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . uniqid();
            Cache::put($key, true, 10);
            $result = Cache::get($key);
            Cache::forget($key);
            return ['status' => $result ? 'up' : 'down'];
        } catch (\Throwable $e) {
            return ['status' => 'down', 'error' => 'Cache unavailable'];
        }
    }
}
