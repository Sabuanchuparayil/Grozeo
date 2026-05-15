<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EnvironmentValidationProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole() || $this->app->runningUnitTests()) {
            return;
        }

        $this->validateRequiredEnvVars();
    }

    private function validateRequiredEnvVars(): void
    {
        $configMap = [
            'APP_KEY' => 'app.key',
            'DB_HOST' => 'database.connections.mysql.host',
            'DB_DATABASE' => 'database.connections.mysql.database',
            'DB_USERNAME' => 'database.connections.mysql.username',
            'DB_PASSWORD' => 'database.connections.mysql.password',
            'REDIS_HOST' => 'database.redis.default.host',
            'JWT_SECRET' => 'jwt.secret',
        ];

        $missing = [];
        foreach ($configMap as $envName => $configKey) {
            if (empty(config($configKey))) {
                $missing[] = $envName;
            }
        }

        if (!empty($missing)) {
            throw new \RuntimeException(
                'Missing required configuration: ' . implode(', ', $missing)
                . '. Ensure .env is present and all required values are set.'
            );
        }

        if (app()->isProduction()) {
            if (config('app.debug', false)) {
                \Log::warning('APP_DEBUG is enabled in production — disable it to prevent data leaks.');
            }

            if (config('app.env') !== 'production') {
                \Log::warning('APP_ENV is not set to "production" in a production environment.');
            }
        }
    }
}
