<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\SecurityHeaders;

class SecurityHeadersTest extends TestCase
{
    private SecurityHeaders $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new SecurityHeaders();
    }

    public function test_adds_x_content_type_options_header(): void
    {
        $response = $this->runMiddleware();
        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
    }

    public function test_adds_x_frame_options_header(): void
    {
        $response = $this->runMiddleware();
        $this->assertEquals('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
    }

    public function test_adds_x_xss_protection_header(): void
    {
        $response = $this->runMiddleware();
        $this->assertEquals('1; mode=block', $response->headers->get('X-XSS-Protection'));
    }

    public function test_adds_referrer_policy_header(): void
    {
        $response = $this->runMiddleware();
        $this->assertEquals('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    public function test_removes_server_header(): void
    {
        $response = $this->runMiddleware();
        $this->assertEmpty($response->headers->get('Server'));
    }

    public function test_removes_x_powered_by_header(): void
    {
        $response = $this->runMiddleware();
        $this->assertEmpty($response->headers->get('X-Powered-By'));
    }

    private function runMiddleware(): Response
    {
        $request = Request::create('/api/test', 'GET');
        $response = $this->middleware->handle($request, function () {
            return new Response('OK');
        });
        return $response;
    }
}
