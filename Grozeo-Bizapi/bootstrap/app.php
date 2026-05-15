<?php

// Suppress PHP 8.5 deprecation for PDO::MYSQL_ATTR_SSL_CA in vendor code
// (Laravel framework's config/database.php references the old constant)
set_error_handler(function ($severity, $message, $file, $line) {
    if ($severity === E_DEPRECATED
        && str_contains($message, 'PDO::MYSQL_ATTR_SSL_CA')
        && str_contains($file, 'vendor/')) {
        return true;
    }
    return false;
}, E_DEPRECATED);

/*
|--------------------------------------------------------------------------
| Create The Application — Laravel 11
|--------------------------------------------------------------------------
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
