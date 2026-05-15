<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\OptionCors;

class OptionCorsTest extends TestCase
{
    private OptionCors $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new OptionCors();
    }

    public function test_preflight_returns_204(): void
    {
        config(['cors.allowed_origins' => ['https://example.com']]);

        $request = Request::create('/api/test', 'OPTIONS');
        $request->headers->set('Origin', 'https://example.com');

        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function test_adds_cors_headers_for_allowed_origin(): void
    {
        config(['cors.allowed_origins' => ['https://example.com']]);
        config(['cors.allowed_methods' => ['GET', 'POST']]);
        config(['cors.allowed_headers' => ['Content-Type', 'Authorization']]);

        $request = Request::create('/api/test', 'GET');
        $request->headers->set('Origin', 'https://example.com');

        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertEquals('https://example.com', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    public function test_does_not_add_cors_for_disallowed_origin(): void
    {
        config(['cors.allowed_origins' => ['https://example.com']]);

        $request = Request::create('/api/test', 'GET');
        $request->headers->set('Origin', 'https://evil.com');

        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertNull($response->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_does_not_add_cors_without_origin_header(): void
    {
        config(['cors.allowed_origins' => ['https://example.com']]);

        $request = Request::create('/api/test', 'GET');

        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });

        $this->assertNull($response->headers->get('Access-Control-Allow-Origin'));
    }
}
