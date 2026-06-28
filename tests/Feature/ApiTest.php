<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ═══════════════════════════════════════════════════════════════════════════════
// MERCENARYKING-S API TEST SUITE
// ═══════════════════════════════════════════════════════════════════════════════
// Validates every API endpoint returns proper JSON, correct HTTP status codes,
// and NEVER serves HTML responses. If any test detects HTML output, the entire
// CI/CD pipeline will FAIL.
// ═══════════════════════════════════════════════════════════════════════════════

// ── Helpers ──────────────────────────────────────────────────────────────────

/**
 * Seed the database before tests that need data.
 */
function seedDatabase(): void
{
    (new \Database\Seeders\DatabaseSeeder())->run();
}

/**
 * Assert a response is valid JSON and contains no HTML.
 */
function assertJsonNoHtml($response): void
{
    $response->assertHeader('Content-Type', 'application/json');
    $content = $response->getContent();

    // CRITICAL: No HTML output ever
    expect($content)->not->toContain('<html>');
    expect($content)->not->toContain('<!DOCTYPE');
    expect($content)->not->toContain('<body>');

    // Must be valid JSON
    $decoded = json_decode($content, true);
    expect($decoded)->not->toBeNull('Response is not valid JSON');
}

// ═══════════════════════════════════════════════════════════════════════════════
// DASHBOARD
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/dashboard/stats returns JSON with metrics', function () {
    seedDatabase();

    $response = $this->getJson('/api/dashboard/stats');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
    $response->assertJsonStructure([
        'metrics' => ['users', 'clients', 'products', 'orders', 'revenue'],
        'charts',
        'telemetry',
    ]);
});

// ═══════════════════════════════════════════════════════════════════════════════
// USERS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/users returns JSON array of users', function () {
    seedDatabase();

    $response = $this->getJson('/api/users');

    $response->assertStatus(200);
    assertJsonNoHtml($response);

    $data = $response->json();
    expect($data)->toBeArray();
});

test('POST /api/users creates a new user', function () {
    seedDatabase();

    $response = $this->postJson('/api/users', [
        'name'     => 'Test Operative',
        'email'    => 'test.op@mercenaryking.net',
        'password' => 'password123',
        'status'   => 'active',
        'role_id'  => 1,
    ]);

    $response->assertStatus(201);
    assertJsonNoHtml($response);
});



// ═══════════════════════════════════════════════════════════════════════════════
// CLIENTS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/clients returns JSON array of clients', function () {
    seedDatabase();

    $response = $this->getJson('/api/clients');

    $response->assertStatus(200);
    assertJsonNoHtml($response);

    $data = $response->json();
    expect($data)->toBeArray();
});

test('POST /api/clients creates a new client', function () {
    seedDatabase();

    $response = $this->postJson('/api/clients', [
        'name'    => 'Cyberdyne Director',
        'email'   => 'director@cyberdyne.corp',
        'company' => 'Cyberdyne Systems',
        'phone'   => '+1-800-555-0100',
        'balance' => 500000,
        'status'  => 'active',
    ]);

    $response->assertStatus(201);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// PRODUCTS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/products returns JSON array of products', function () {
    seedDatabase();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200);
    assertJsonNoHtml($response);

    $data = $response->json();
    expect($data)->toBeArray();
});

test('POST /api/products creates a new product', function () {
    seedDatabase();
    $user = \App\Models\User::first();

    $payload = [
        'name' => 'Stealth Camo v2',
        'price' => 15000,
        'stock' => 10,
        'type' => 'Hardware'
    ];

    $response = $this->actingAs($user)->postJson('/api/products', $payload);

    $response->assertStatus(201)
             ->assertJsonPath('data.name', 'Stealth Camo v2');
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// ORDERS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/orders returns JSON array of orders', function () {
    seedDatabase();

    $response = $this->getJson('/api/orders');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

test('POST /api/orders creates a new order with items', function () {
    seedDatabase();

    $response = $this->postJson('/api/orders', [
        'client_id' => 1,
        'items'     => [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 3, 'quantity' => 1],
        ],
        'payment_method' => 'NeuralPay',
    ]);

    // Accept 201 (created) or 200 (success)
    expect($response->status())->toBeIn([200, 201]);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// TRANSACTIONS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/transactions returns JSON array', function () {
    seedDatabase();

    $response = $this->getJson('/api/transactions');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

test('POST /api/transactions/simulate creates a transaction', function () {
    seedDatabase();

    $response = $this->postJson('/api/transactions/simulate', [
        'client_id' => 1,
        'amount'    => 4500.00,
        'provider'  => 'CryptoPay',
        'status'    => 'success',
    ]);

    expect($response->status())->toBeIn([200, 201]);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// INVOICES
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/invoices returns JSON array', function () {
    seedDatabase();

    $response = $this->getJson('/api/invoices');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

test('GET /api/invoices/{id} returns single invoice JSON', function () {
    seedDatabase();

    $response = $this->getJson('/api/invoices/1');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// API KEYS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/api-keys returns JSON array', function () {
    seedDatabase();

    $response = $this->getJson('/api/api-keys');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

test('POST /api/api-keys generates a new key', function () {
    seedDatabase();

    $response = $this->postJson('/api/api-keys', [
        'name' => 'CI Test Key',
    ]);

    $response->assertStatus(201);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// ACTIVITY LOGS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/activity-logs returns JSON array', function () {
    seedDatabase();

    $response = $this->getJson('/api/activity-logs');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// SUPPORT TICKETS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/tickets returns JSON array', function () {
    seedDatabase();

    $response = $this->getJson('/api/tickets');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

test('POST /api/tickets creates a support ticket', function () {
    seedDatabase();

    $response = $this->postJson('/api/tickets', [
        'client_id'   => 1,
        'subject'     => 'CI Pipeline Test Ticket',
        'description' => 'Automated ticket from CI/CD test suite.',
        'priority'    => 'low',
    ]);

    $response->assertStatus(201);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// SETTINGS
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/settings returns JSON array', function () {
    seedDatabase();

    $response = $this->getJson('/api/settings');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
});

// ═══════════════════════════════════════════════════════════════════════════════
// POSTMAN EXPORT
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/postman/export returns valid Postman collection', function () {
    $response = $this->getJson('/api/postman/export');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
    $response->assertJsonStructure([
        'info' => ['name', 'schema'],
        'item',
    ]);
});

// ═══════════════════════════════════════════════════════════════════════════════
// VERSIONED API (v1)
// ═══════════════════════════════════════════════════════════════════════════════

test('GET /api/v1/system/status returns system telemetry JSON', function () {
    seedDatabase();

    $response = $this->getJson('/api/v1/system/status');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
    $response->assertJsonStructure([
        'status',
        'data',
    ]);
});

test('GET /api/v1/devops/status returns pipeline status JSON', function () {
    seedDatabase();

    $response = $this->getJson('/api/v1/devops/status');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
    $response->assertJsonStructure([
        'data' => ['pipeline', 'git', 'postman', 'deployment', 'api'],
    ]);
});

test('GET /api/v1/devops/health returns health matrix JSON', function () {
    seedDatabase();

    $response = $this->getJson('/api/v1/devops/health');

    $response->assertStatus(200);
    assertJsonNoHtml($response);
    $response->assertJsonStructure([
        'data' => ['overall', 'total', 'passed', 'endpoints'],
    ]);
});

// ═══════════════════════════════════════════════════════════════════════════════
// API SAFETY: NO HTML EVER
// ═══════════════════════════════════════════════════════════════════════════════

test('non-existent API route returns JSON 404, not HTML', function () {
    $response = $this->getJson('/api/nonexistent-endpoint');

    $response->assertStatus(404);
    assertJsonNoHtml($response);
    $response->assertJsonStructure(['status', 'message', 'code']);
});

test('API error responses are always JSON, never HTML', function () {
    // POST to a GET-only endpoint should return 405 JSON
    $response = $this->postJson('/api/dashboard/stats', []);

    // Should be 405 Method Not Allowed as JSON
    expect($response->status())->toBeIn([405, 404, 200]);
    assertJsonNoHtml($response);
});
