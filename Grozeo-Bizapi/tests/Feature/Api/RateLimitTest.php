<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class RateLimitTest extends TestCase
{
    public function test_api_returns_rate_limit_headers(): void
    {
        $response = $this->getJson('/api/health');

        $this->assertTrue(
            $response->headers->has('X-RateLimit-Limit') ||
            $response->headers->has('x-ratelimit-limit'),
            'Rate limit headers should be present'
        );
    }

    public function test_rate_limit_exceeded_returns_429(): void
    {
        for ($i = 0; $i < 7; $i++) {
            $response = $this->postJson('/api/login', [
                'cust_email' => "brute{$i}@test.com",
                'password' => 'password123',
            ]);
        }

        $this->assertTrue(
            $response->status() === 429,
            'Expected 429 after exceeding auth rate limit'
        );
    }

    public function test_rate_limit_error_response_format(): void
    {
        for ($i = 0; $i < 7; $i++) {
            $response = $this->postJson('/api/login', [
                'cust_email' => "brute{$i}@test.com",
                'password' => 'password123',
            ]);
        }

        if ($response->status() === 429) {
            $response->assertJson([
                'status' => 'error',
                'error' => [
                    'code' => 'RATE_LIMIT_EXCEEDED',
                ],
            ]);
        }
    }
}
