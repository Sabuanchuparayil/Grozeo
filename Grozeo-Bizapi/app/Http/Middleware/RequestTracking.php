<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RequestTracking
{
    public function handle(Request $request, Closure $next)
    {
        $correlationId = $request->header('X-Correlation-ID', Str::uuid()->toString());
        $startTime = microtime(true);

        $request->attributes->set('correlation_id', $correlationId);

        Log::shareContext([
            'correlation_id' => $correlationId,
        ]);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $response->headers->set('X-Correlation-ID', $correlationId);
        $response->headers->set('X-Response-Time', $duration . 'ms');

        $logLevel = $response->getStatusCode() >= 500 ? 'error' : ($response->getStatusCode() >= 400 ? 'warning' : 'info');

        Log::$logLevel('Request completed', [
            'correlation_id' => $correlationId,
            'request_method' => $request->method(),
            'request_path' => $request->path(),
            'client_ip' => $request->ip(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ]);

        return $response;
    }
}
