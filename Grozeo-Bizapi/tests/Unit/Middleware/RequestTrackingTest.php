<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\RequestTracking;

class RequestTrackingTest extends TestCase
{
    private RequestTracking $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new RequestTracking();
    }

    public function test_adds_correlation_id_header(): void
    {
        $request = Request::create('/api/test', 'GET');
        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertNotEmpty($response->headers->get('X-Correlation-ID'));
    }

    public function test_adds_response_time_header(): void
    {
        $request = Request::create('/api/test', 'GET');
        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertNotEmpty($response->headers->get('X-Response-Time'));
    }

    public function test_uses_existing_correlation_id_if_provided(): void
    {
        $existingId = 'test-correlation-id-123';
        $request = Request::create('/api/test', 'GET');
        $request->headers->set('X-Correlation-ID', $existingId);

        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertEquals($existingId, $response->headers->get('X-Correlation-ID'));
    }

    public function test_correlation_id_is_uuid_format(): void
    {
        $request = Request::create('/api/test', 'GET');
        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $correlationId = $response->headers->get('X-Correlation-ID');
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $correlationId
        );
    }
}
