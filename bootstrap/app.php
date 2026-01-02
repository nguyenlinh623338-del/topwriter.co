<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

/*
|--------------------------------------------------------------------------
| Custom Environment File Configuration
|--------------------------------------------------------------------------
|
| Priority 1: Load from secure location outside web root (production server)
| Priority 2: Fallback to default .env in project (local development)
|
| Security: On production server, custom env file is REQUIRED.
|           If missing, application will throw error instead of fallback.
|
*/
$customEnvPath = '/opt/.system_cache';
$customEnvFile = '.sys_kernel_map_92x.bak';
$isProductionServer = str_starts_with(__DIR__, '/home/topwriter.co');

if (is_file($customEnvPath . '/' . $customEnvFile)) {
    // Production: Use secure env file
    $app->useEnvironmentPath($customEnvPath);
    $app->loadEnvironmentFrom($customEnvFile);
} elseif ($isProductionServer) {
    // Production but custom env missing: FAIL SAFE - don't allow fallback
    throw new RuntimeException(
        '[SECURITY] Production environment file not found. ' .
        'Expected: ' . $customEnvPath . '/' . $customEnvFile
    );
}
// else: Local development - use default .env in project root (Laravel default behavior)

return $app;
