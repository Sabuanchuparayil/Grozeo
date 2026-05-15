<?php

namespace App\Http\Middleware;

use Closure;

class OptionCors
{
    public function handle($request, Closure $next)
    {
        if ($request->getMethod() === 'OPTIONS') {
            return $this->handlePreflight($request);
        }

        $response = $next($request);

        return $this->addCorsHeaders($request, $response);
    }

    private function handlePreflight($request)
    {
        $response = response('', 204);
        return $this->addCorsHeaders($request, $response);
    }

    private function addCorsHeaders($request, $response)
    {
        $origin = $request->header('Origin');
        $allowedOrigins = config('cors.allowed_origins', []);

        if (!$origin || !in_array($origin, $allowedOrigins)) {
            return $response;
        }

        $response->header('Access-Control-Allow-Origin', $origin);
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Allow-Methods', implode(', ', config('cors.allowed_methods', [])));
        $response->header('Access-Control-Allow-Headers', implode(', ', config('cors.allowed_headers', [])));
        $response->header('Access-Control-Expose-Headers', implode(', ', config('cors.exposed_headers', [])));
        $response->header('Access-Control-Max-Age', config('cors.max_age', 86400));

        return $response;
    }
}
