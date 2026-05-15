<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class ErrorResponseTest extends TestCase
{
    public function test_404_returns_json_error(): void
    {
        $response = $this->getJson('/api/this-route-does-not-exist');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'error',
            'error' => [
                'code' => 'NOT_FOUND',
                'msg' => 'Endpoint not found.',
            ],
        ]);
    }

    public function test_405_method_not_allowed(): void
    {
        $response = $this->deleteJson('/api/login');

        $response->assertStatus(405);
        $response->assertJson([
            'status' => 'error',
            'error' => [
                'code' => 'METHOD_NOT_ALLOWED',
            ],
        ]);
    }

    public function test_error_response_never_leaks_stack_trace_in_production(): void
    {
        $this->app['env'] = 'production';

        $response = $this->getJson('/api/this-route-does-not-exist');

        $response->assertJsonMissing(['trace']);
        $response->assertJsonMissing(['file']);
        $response->assertJsonMissing(['line']);
    }

    public function test_security_headers_present_on_error(): void
    {
        $response = $this->getJson('/api/this-route-does-not-exist');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_correlation_id_present_on_error(): void
    {
        $response = $this->getJson('/api/this-route-does-not-exist');

        $this->assertNotEmpty($response->headers->get('X-Correlation-ID'));
    }
}
