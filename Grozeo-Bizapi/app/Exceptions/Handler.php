<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        MsgException::class,
        OfferException::class,
        AuthenticationException::class,
        AuthorizationException::class,
        ValidationException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Unhandled exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()?->fullUrl(),
                'method' => request()?->method(),
            ]);
        });
    }

    public function render($request, Throwable $exception)
    {
        return $this->renderApiException($request, $exception);
    }

    private function renderApiException($request, Throwable $exception): \Illuminate\Http\JsonResponse
    {
        $status = 500;
        $message = 'An unexpected error occurred.';
        $errors = null;
        $code = 'SERVER_ERROR';

        switch (true) {
            case $exception instanceof ValidationException:
                $status = 422;
                $message = 'Validation failed.';
                $errors = $exception->errors();
                $code = 'VALIDATION_ERROR';
                break;

            case $exception instanceof AuthenticationException:
                $status = 401;
                $message = $exception->getMessage() === 'Token has expired'
                    ? 'Token has expired'
                    : 'Unauthenticated.';
                $code = 'UNAUTHENTICATED';
                break;

            case $exception instanceof AuthorizationException:
                $status = 403;
                $message = 'Unauthorized.';
                $code = 'UNAUTHORIZED';
                break;

            case $exception instanceof ModelNotFoundException:
                $status = 404;
                $model = class_basename($exception->getModel());
                $message = "{$model} not found.";
                $code = 'RESOURCE_NOT_FOUND';
                break;

            case $exception instanceof NotFoundHttpException:
                $status = 404;
                $message = 'Endpoint not found.';
                $code = 'NOT_FOUND';
                break;

            case $exception instanceof MethodNotAllowedHttpException:
                $status = 405;
                $message = 'Method not allowed.';
                $code = 'METHOD_NOT_ALLOWED';
                break;

            case $exception instanceof TooManyRequestsHttpException:
                $status = 429;
                $message = 'Too many requests. Please try again later.';
                $code = 'RATE_LIMIT_EXCEEDED';
                break;

            case $exception instanceof MsgException:
                $status = 406;
                $message = $exception->getMessage();
                $code = 'BUSINESS_ERROR';
                break;

            case $exception instanceof ErrException:
                $status = 400;
                $message = $exception->getMessage();
                $code = 'BAD_REQUEST';
                break;

            default:
                if (method_exists($exception, 'getStatusCode')) {
                    $status = $exception->getStatusCode();
                }
                if (!app()->isProduction()) {
                    $message = $exception->getMessage();
                }
                break;
        }

        $response = [
            'status' => 'error',
            'error' => [
                'code' => $code,
                'msg' => $message,
            ],
        ];

        if ($errors !== null) {
            $response['error']['details'] = $errors;
        }

        if (!app()->isProduction() && $status >= 500) {
            $response['error']['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->take(5)->map(function ($frame) {
                    return Arr::only($frame, ['file', 'line', 'function', 'class']);
                })->toArray(),
            ];
        }

        return response()->json($response, $status);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->renderApiException($request, $exception);
    }
}
