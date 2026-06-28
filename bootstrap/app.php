<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Force every /api/* request to receive JSON responses
        $middleware->api(prepend: [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // ── Validation errors (422) ─────────────────────────────────────────
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $e->errors(),
                    'code'    => 422,
                ], 422);
            }
        });

        // ── Unauthenticated (401) ───────────────────────────────────────────
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated. Grid access denied.',
                    'code'    => 401,
                ], 401);
            }
        });

        // ── Model Not Found (404) ───────────────────────────────────────────
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                $model = class_basename($e->getModel());
                return response()->json([
                    'status'  => false,
                    'message' => "{$model} node not found in the grid.",
                    'code'    => 404,
                ], 404);
            }
        });

        // ── Route Not Found (404) ───────────────────────────────────────────
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'API endpoint node does not exist in this grid sector.',
                    'code'    => 404,
                ], 404);
            }
        });

        // ── Method Not Allowed (405) ────────────────────────────────────────
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'HTTP transmission method not allowed on this endpoint.',
                    'code'    => 405,
                ], 405);
            }
        });

        // ── Generic HTTP exceptions (e.g. 403, 429, etc.) ──────────────────
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => $e->getMessage() ?: 'HTTP transmission error.',
                    'code'    => $e->getStatusCode(),
                ], $e->getStatusCode());
            }
        });

        // ── Catch-all: any unhandled throwable (500) ────────────────────────
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                return response()->json([
                    'status'  => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal grid fault. Contact system overlord.',
                    'code'    => $code,
                ], $code >= 100 && $code < 600 ? $code : 500);
            }
        });

    })->create();
