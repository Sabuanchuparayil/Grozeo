<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    protected $except = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        $sanitized = $this->sanitize($input);
        $request->merge($sanitized);

        return $next($request);
    }

    private function sanitize(array $input): array
    {
        foreach ($input as $key => $value) {
            if (in_array($key, $this->except, true)) {
                continue;
            }

            if (is_string($value)) {
                $input[$key] = strip_tags($value);
            } elseif (is_array($value)) {
                $input[$key] = $this->sanitize($value);
            }
        }

        return $input;
    }
}
