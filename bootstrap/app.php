<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Enums\HttpStatus;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability'  => CheckForAnyAbility::class,
        ]);

        $middleware->statefulApi();

        $middleware->validateCsrfTokens(except: [
            'api/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            ValidationException::class,
            ModelNotFoundException::class,
        ]);

        $exceptions->context(fn() => [
            'user_id' => Auth::id(),
            'env' => env('APP_ENV'),
        ]);

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                return match (true) {
                    $e instanceof ValidationException => response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $e->errors(),
                    ], HttpStatus::UNPROCESSABLE_ENTITY->value),

                    $e instanceof ModelNotFoundException => response()->json([
                        'success' => false,
                        'message' => 'Resource not found',
                    ], HttpStatus::NOT_FOUND->value),

                    $e instanceof QueryException => response()->json([
                        'success' => false,
                        'message' => $e->errorInfo[1] === 1062 ? 'Duplicate data detected' : 'Database error',
                        'error' => $e->getMessage(),
                    ], HttpStatus::BAD_REQUEST->value),

                    default => response()->json([
                        'success' => false,
                        'message' => 'Server error',
                        'error' => $e->getMessage(),
                    ], HttpStatus::INTERNAL_SERVER_ERROR->value),
                };
            }
        });
    })
    ->create();
