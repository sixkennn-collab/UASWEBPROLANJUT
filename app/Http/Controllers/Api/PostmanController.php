<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostmanController extends Controller
{
    /**
     * GET /api/postman/export
     * Returns a downloadable Postman Collection v2.1.0 JSON.
     */
    public function export(Request $request)
    {
        $base = rtrim(url('/'), '/');

        $collection = [
            'info' => [
                '_postman_id' => 'mk-s-v2-' . time(),
                'name'        => 'MERCENARYKING-S // Enterprise SaaS API',
                'description' => 'Complete REST API collection for the MercenaryKing-S cyberpunk SaaS platform.',
                'schema'      => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'variable' => [
                ['key' => 'baseUrl', 'value' => $base, 'type' => 'string'],
            ],
            'item' => [

                // ── DASHBOARD ─────────────────────────────────────────────────
                $this->folder('Dashboard & Telemetry', [
                    $this->req('GET Dashboard Stats', 'GET', '/api/dashboard/stats'),
                ]),

                // ── USERS ─────────────────────────────────────────────────────
                $this->folder('User Node Management', [
                    $this->req('GET All Users',     'GET',    '/api/users'),
                    $this->req('POST Create User',  'POST',   '/api/users', ['name' => 'Kestrel-09', 'email' => 'k9@mk.net', 'password' => 'password', 'status' => 'active', 'role_id' => 1]),
                    $this->req('PUT Update User',   'PUT',    '/api/users/1', ['name' => 'Kestrel-09 Updated', 'status' => 'inactive']),
                    $this->req('DELETE Purge User', 'DELETE', '/api/users/1'),
                ]),

                // ── CLIENTS ───────────────────────────────────────────────────
                $this->folder('Corp Client Registry', [
                    $this->req('GET All Clients',     'GET',    '/api/clients'),
                    $this->req('POST Create Client',  'POST',   '/api/clients', ['name' => 'Cyberdyne Lead', 'email' => 'ceo@cyberdyne.io', 'company' => 'Cyberdyne Systems', 'phone' => '+1-800-000-0000', 'balance' => 500000, 'status' => 'active']),
                    $this->req('PUT Update Client',   'PUT',    '/api/clients/1', ['balance' => 999999.99]),
                    $this->req('DELETE Remove Client','DELETE', '/api/clients/1'),
                ]),

                // ── PRODUCTS ──────────────────────────────────────────────────
                $this->folder('Asset Specification Catalog', [
                    $this->req('GET All Products',     'GET',    '/api/products'),
                    $this->req('POST Create Product',  'POST',   '/api/products', ['name' => 'Cyber Sword EX', 'sku' => 'WP-SWD-EX', 'price' => 9500, 'stock' => 20, 'category' => 'Weapons', 'status' => 'active']),
                    $this->req('PUT Update Product',   'PUT',    '/api/products/1', ['stock' => 50, 'price' => 19000]),
                    $this->req('DELETE Purge Product', 'DELETE', '/api/products/1'),
                ]),

                // ── ORDERS ────────────────────────────────────────────────────
                $this->folder('Order & Transaction Grid', [
                    $this->req('GET All Orders',       'GET',  '/api/orders'),
                    $this->req('POST Create Order',    'POST', '/api/orders', ['client_id' => 1, 'items' => [['product_id' => 1, 'quantity' => 2], ['product_id' => 3, 'quantity' => 1]], 'payment_method' => 'NeuralPay', 'simulate_payment' => true]),
                    $this->req('PUT Update Order Status','PUT','/api/orders/1/status', ['status' => 'completed', 'payment_status' => 'paid']),
                ]),

                // ── TRANSACTIONS ──────────────────────────────────────────────
                $this->folder('Payment Gateway', [
                    $this->req('GET Transactions',       'GET',  '/api/transactions'),
                    $this->req('POST Simulate Payment',  'POST', '/api/transactions/simulate', ['client_id' => 1, 'amount' => 4500.00, 'provider' => 'CryptoPay', 'status' => 'success']),
                ]),

                // ── INVOICES ──────────────────────────────────────────────────
                $this->folder('Invoice Center', [
                    $this->req('GET All Invoices',   'GET', '/api/invoices'),
                    $this->req('GET Single Invoice', 'GET', '/api/invoices/1'),
                    $this->req('PUT Update Invoice', 'PUT', '/api/invoices/1/status', ['status' => 'paid']),
                ]),

                // ── API KEYS ──────────────────────────────────────────────────
                $this->folder('API Access Keys', [
                    $this->req('GET API Keys',       'GET',  '/api/api-keys'),
                    $this->req('POST Generate Key',  'POST', '/api/api-keys', ['name' => 'New Access Node']),
                    $this->req('PUT Revoke Key',     'PUT',  '/api/api-keys/1/revoke'),
                ]),

                // ── AUDIT LOGS ────────────────────────────────────────────────
                $this->folder('Audit Logs', [
                    $this->req('GET Audit Logs', 'GET', '/api/activity-logs'),
                ]),

                // ── SUPPORT TICKETS ───────────────────────────────────────────
                $this->folder('Support Channels', [
                    $this->req('GET All Tickets',   'GET',    '/api/tickets'),
                    $this->req('POST Open Ticket',  'POST',   '/api/tickets', ['client_id' => 1, 'subject' => 'Interstellar Signal Loss', 'description' => 'Grid comms offline.', 'priority' => 'high']),
                    $this->req('PUT Update Ticket', 'PUT',    '/api/tickets/1', ['status' => 'resolved']),
                    $this->req('DELETE Close Ticket','DELETE', '/api/tickets/1'),
                ]),

                // ── SETTINGS ──────────────────────────────────────────────────
                $this->folder('Matrix Settings', [
                    $this->req('GET All Settings',    'GET', '/api/settings'),
                    $this->req('PUT Update Settings', 'PUT', '/api/settings', ['settings' => [['key' => 'grid_status', 'value' => 'MAINTENANCE']]]),
                ]),

                // ── POSTMAN EXPORT ────────────────────────────────────────────
                $this->folder('Postman', [
                    $this->req('GET Export Collection', 'GET', '/api/postman/export'),
                ]),
            ],
        ];

        return response()->json($collection, 200, [
            'Content-Disposition' => 'attachment; filename="mercenaryking_s_postman_collection.json"',
        ]);
    }

    // ── helpers ────────────────────────────────────────────────────────────────

    private function folder(string $name, array $items): array
    {
        return ['name' => $name, 'item' => $items];
    }

    private function req(string $name, string $method, string $path, ?array $body = null): array
    {
        $item = [
            'name'    => $name,
            'request' => [
                'method' => $method,
                'header' => [
                    ['key' => 'Accept',       'value' => 'application/json', 'type' => 'text'],
                    ['key' => 'Content-Type', 'value' => 'application/json', 'type' => 'text'],
                ],
                'url' => [
                    'raw'  => '{{baseUrl}}' . $path,
                    'host' => ['{{baseUrl}}'],
                    'path' => array_values(array_filter(explode('/', ltrim(strtok($path, '?'), '/')))),
                ],
            ],
        ];

        if ($body !== null) {
            $item['request']['body'] = [
                'mode'    => 'raw',
                'raw'     => json_encode($body, JSON_PRETTY_PRINT),
                'options' => ['raw' => ['language' => 'json']],
            ];
        }

        return $item;
    }
}
