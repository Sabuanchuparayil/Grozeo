<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* Log::debug(Request::getRequestUri());
Log::debug(Request::all()); */
Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    $checks = ['status' => 'healthy', 'service' => 'grozeo-scheduler', 'timestamp' => now()->toIso8601String(), 'checks' => []];
    try {
        $start = microtime(true);
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $checks['checks']['database'] = ['status' => 'up', 'latency_ms' => round((microtime(true) - $start) * 1000, 2)];
    } catch (\Throwable $e) {
        $checks['checks']['database'] = ['status' => 'down'];
        $checks['status'] = 'degraded';
    }
    try {
        \Illuminate\Support\Facades\Redis::ping();
        $checks['checks']['redis'] = ['status' => 'up'];
    } catch (\Throwable $e) {
        $checks['checks']['redis'] = ['status' => 'down'];
        $checks['status'] = 'degraded';
    }
    return response()->json($checks, $checks['status'] === 'healthy' ? 200 : 503);
});

Route::get('/health/ping', function () {
    return response()->json(['status' => 'ok', 'service' => 'grozeo-scheduler']);
});