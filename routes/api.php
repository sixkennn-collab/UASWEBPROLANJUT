<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ApiKeyController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\PostmanController;
use App\Http\Controllers\Api\SystemController;
use App\Http\Controllers\Api\DevOpsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Base routes (backward compatible, unversioned)
| + Versioned routes (/api/v1/*, /api/v2/*)
|
*/

// ═══════════════════════════════════════════════════════════════════════════════
// UNVERSIONED ROUTES (Backward Compatibility)
// ═══════════════════════════════════════════════════════════════════════════════

// Dashboard Stats
Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

// Users CRUD
Route::apiResource('/users', UserController::class);

// Clients CRUD
Route::apiResource('/clients', ClientController::class);

// Products CRUD
Route::apiResource('/products', ProductController::class);

// Orders & Transactions
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

// Transactions
Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions/simulate', [TransactionController::class, 'simulate']);

// Invoices
Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
Route::put('/invoices/{id}/status', [InvoiceController::class, 'updateStatus']);

// API Keys
Route::get('/api-keys', [ApiKeyController::class, 'index']);
Route::post('/api-keys', [ApiKeyController::class, 'store']);
Route::put('/api-keys/{id}/revoke', [ApiKeyController::class, 'revoke']);

// Activity Logs
Route::get('/activity-logs', [ActivityLogController::class, 'index']);

// Support Tickets
Route::apiResource('/tickets', SupportTicketController::class);

// Settings
Route::get('/settings', [SettingController::class, 'index']);
Route::put('/settings', [SettingController::class, 'update']);

// Postman Export
Route::get('/postman/export', [PostmanController::class, 'export']);


// ═══════════════════════════════════════════════════════════════════════════════
// API v1 — VERSIONED ROUTES
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('v1')->group(function () {

    // ── Dashboard ────────────────────────────────────────────────────────────
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    // ── Users ────────────────────────────────────────────────────────────────
    Route::apiResource('/users', UserController::class);

    // ── Clients ──────────────────────────────────────────────────────────────
    Route::apiResource('/clients', ClientController::class);

    // ── Products ─────────────────────────────────────────────────────────────
    Route::apiResource('/products', ProductController::class);

    // ── Orders ───────────────────────────────────────────────────────────────
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    // ── Transactions ─────────────────────────────────────────────────────────
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions/simulate', [TransactionController::class, 'simulate']);

    // ── Invoices ─────────────────────────────────────────────────────────────
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::put('/invoices/{id}/status', [InvoiceController::class, 'updateStatus']);

    // ── API Keys ─────────────────────────────────────────────────────────────
    Route::get('/api-keys', [ApiKeyController::class, 'index']);
    Route::post('/api-keys', [ApiKeyController::class, 'store']);
    Route::put('/api-keys/{id}/revoke', [ApiKeyController::class, 'revoke']);

    // ── Activity Logs ────────────────────────────────────────────────────────
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);

    // ── Support Tickets ──────────────────────────────────────────────────────
    Route::apiResource('/tickets', SupportTicketController::class);

    // ── Settings ─────────────────────────────────────────────────────────────
    Route::get('/settings', [SettingController::class, 'index']);
    Route::put('/settings', [SettingController::class, 'update']);

    // ── Postman Export ───────────────────────────────────────────────────────
    Route::get('/postman/export', [PostmanController::class, 'export']);

    // ── System Telemetry ─────────────────────────────────────────────────────
    Route::get('/system/status', [SystemController::class, 'status']);
    Route::get('/system/logs', [SystemController::class, 'logs']);

    // ── DevOps Control ───────────────────────────────────────────────────────
    Route::get('/devops/status', [DevOpsController::class, 'status']);
    Route::get('/devops/health', [DevOpsController::class, 'health']);
});


// ═══════════════════════════════════════════════════════════════════════════════
// API v2 — FUTURE READY (STUB)
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('v2')->group(function () {

    // v2 endpoints will be added here when breaking changes are introduced.
    // Current v1 endpoints remain stable and backward-compatible.

    Route::get('/status', function () {
        return response()->json([
            'status'  => true,
            'message' => 'API v2 is under development. Use /api/v1/ for stable endpoints.',
            'version' => 'v2-alpha',
            'v1_url'  => url('/api/v1'),
        ]);
    });
});
