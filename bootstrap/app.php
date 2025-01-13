<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([\App\Http\Middleware\CORS::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Unauthenticated Exception handler
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                Log::error($e);
                return response()->json([
                    'status' => false,
                    'error' => "You're not authenticated, please login"
                ], 401);
            }
        });

        // Record not found Exception handler
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'error' => 'The requested resource does not exist'
                ], 404);
            }
        });

        // Generic Exception handler
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {
                Log::error($e);
                return response()->json([
                    'status' => false,
                    'error' => 'Something went wrong, please try again'
                ], 500);
            }
        });
    })->create();
