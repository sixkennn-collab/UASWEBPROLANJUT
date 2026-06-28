<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class ExportPostmanCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postman:export
                            {--output= : Custom output path (default: storage/app/postman/mercenaryking.postman.json)}
                            {--base-url=http://localhost:8000 : Base URL for the API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all API routes as a Postman Collection v2.1.0 JSON file';

    /**
     * Sample request bodies for POST/PUT endpoints.
     */
    private array $sampleBodies = [
        'POST /api/users'                => ['name' => 'Kestrel-09', 'email' => 'k9@mk.net', 'password' => 'password', 'status' => 'active', 'role_id' => 1],
        'POST /api/v1/users'             => ['name' => 'Kestrel-09', 'email' => 'k9@mk.net', 'password' => 'password', 'status' => 'active', 'role_id' => 1],
        'PUT /api/users/{user}'          => ['name' => 'Kestrel-09 Updated', 'status' => 'inactive'],
        'PUT /api/v1/users/{user}'       => ['name' => 'Kestrel-09 Updated', 'status' => 'inactive'],
        'POST /api/clients'              => ['name' => 'Cyberdyne Lead', 'email' => 'ceo@cyberdyne.io', 'company' => 'Cyberdyne Systems', 'phone' => '+1-800-000-0000', 'balance' => 500000, 'status' => 'active'],
        'POST /api/v1/clients'           => ['name' => 'Cyberdyne Lead', 'email' => 'ceo@cyberdyne.io', 'company' => 'Cyberdyne Systems', 'phone' => '+1-800-000-0000', 'balance' => 500000, 'status' => 'active'],
        'PUT /api/clients/{client}'      => ['balance' => 999999.99],
        'PUT /api/v1/clients/{client}'   => ['balance' => 999999.99],
        'POST /api/products'             => ['name' => 'Cyber Sword EX', 'sku' => 'WP-SWD-EX', 'price' => 9500, 'stock' => 20, 'category' => 'Weapons', 'status' => 'active'],
        'POST /api/v1/products'          => ['name' => 'Cyber Sword EX', 'sku' => 'WP-SWD-EX', 'price' => 9500, 'stock' => 20, 'category' => 'Weapons', 'status' => 'active'],
        'PUT /api/products/{product}'    => ['stock' => 50, 'price' => 19000],
        'PUT /api/v1/products/{product}' => ['stock' => 50, 'price' => 19000],
        'POST /api/orders'               => ['client_id' => 1, 'items' => [['product_id' => 1, 'quantity' => 2]], 'payment_method' => 'NeuralPay'],
        'POST /api/v1/orders'            => ['client_id' => 1, 'items' => [['product_id' => 1, 'quantity' => 2]], 'payment_method' => 'NeuralPay'],
        'PUT /api/orders/{id}/status'    => ['status' => 'completed', 'payment_status' => 'paid'],
        'PUT /api/v1/orders/{id}/status' => ['status' => 'completed', 'payment_status' => 'paid'],
        'POST /api/transactions/simulate'     => ['client_id' => 1, 'amount' => 4500.00, 'provider' => 'CryptoPay', 'status' => 'success'],
        'POST /api/v1/transactions/simulate'  => ['client_id' => 1, 'amount' => 4500.00, 'provider' => 'CryptoPay', 'status' => 'success'],
        'PUT /api/invoices/{id}/status'       => ['status' => 'paid'],
        'PUT /api/v1/invoices/{id}/status'    => ['status' => 'paid'],
        'POST /api/api-keys'             => ['name' => 'New Access Node'],
        'POST /api/v1/api-keys'          => ['name' => 'New Access Node'],
        'POST /api/tickets'              => ['client_id' => 1, 'subject' => 'Interstellar Signal Loss', 'description' => 'Grid comms offline.', 'priority' => 'high'],
        'POST /api/v1/tickets'           => ['client_id' => 1, 'subject' => 'Interstellar Signal Loss', 'description' => 'Grid comms offline.', 'priority' => 'high'],
        'PUT /api/tickets/{ticket}'      => ['status' => 'resolved'],
        'PUT /api/v1/tickets/{ticket}'   => ['status' => 'resolved'],
        'PUT /api/settings'              => ['settings' => [['key' => 'grid_status', 'value' => 'MAINTENANCE']]],
        'PUT /api/v1/settings'           => ['settings' => [['key' => 'grid_status', 'value' => 'MAINTENANCE']]],
    ];

    /**
     * Folder grouping rules based on URI segments.
     */
    private array $folderMap = [
        'dashboard'    => 'Dashboard & Telemetry',
        'users'        => 'User Node Management',
        'clients'      => 'Corp Client Registry',
        'products'     => 'Asset Specification Catalog',
        'orders'       => 'Order & Transaction Grid',
        'transactions' => 'Payment Gateway',
        'invoices'     => 'Invoice Center',
        'api-keys'     => 'API Access Keys',
        'activity-logs'=> 'Audit Logs',
        'tickets'      => 'Support Channels',
        'settings'     => 'Matrix Settings',
        'postman'      => 'Postman',
        'system'       => 'System Telemetry',
        'devops'       => 'DevOps Control',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  MERCENARYKING-S // POSTMAN COLLECTION GENERATOR');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');

        $baseUrl = $this->option('base-url');
        $outputPath = $this->option('output') ?? storage_path('app/postman/mercenaryking.postman.json');

        // Ensure output directory exists
        $outputDir = dirname($outputPath);
        if (!File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Collect API routes
        $routes = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => str_starts_with($route->uri(), 'api/'))
            ->values();

        $this->info("  📡 Found {$routes->count()} API routes");

        // Group into folders
        $folders = [];

        foreach ($routes as $route) {
            $methods = array_diff($route->methods(), ['HEAD']);
            $uri = $route->uri();

            foreach ($methods as $method) {
                $folderName = $this->determineFolderName($uri);
                $itemName = strtoupper($method) . ' ' . $this->humanizePath($uri);

                $item = [
                    'name'    => $itemName,
                    'request' => [
                        'method' => strtoupper($method),
                        'header' => [
                            ['key' => 'Accept',       'value' => 'application/json', 'type' => 'text'],
                            ['key' => 'Content-Type', 'value' => 'application/json', 'type' => 'text'],
                        ],
                        'url' => [
                            'raw'  => '{{baseUrl}}/' . $uri,
                            'host' => ['{{baseUrl}}'],
                            'path' => array_values(array_filter(explode('/', $uri))),
                        ],
                    ],
                ];

                // Add sample body for POST/PUT
                $bodyKey = strtoupper($method) . ' /' . $uri;
                if (isset($this->sampleBodies[$bodyKey])) {
                    $item['request']['body'] = [
                        'mode'    => 'raw',
                        'raw'     => json_encode($this->sampleBodies[$bodyKey], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                        'options' => ['raw' => ['language' => 'json']],
                    ];
                }

                $folders[$folderName][] = $item;
            }
        }

        // Build collection
        $collection = [
            'info' => [
                '_postman_id' => 'mercenaryking-s-ci-' . date('Ymd-His'),
                'name'        => 'MERCENARYKING-S // Enterprise SaaS API',
                'description' => "Auto-generated Postman Collection for the MercenaryKing-S cyberpunk SaaS platform.\nGenerated: " . now()->toIso8601String() . "\nRoutes: {$routes->count()}",
                'schema'      => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'variable' => [
                ['key' => 'baseUrl', 'value' => $baseUrl, 'type' => 'string'],
            ],
            'item' => [],
        ];

        foreach ($folders as $folderName => $items) {
            $collection['item'][] = [
                'name' => $folderName,
                'item' => $items,
            ];
        }

        // Write to file
        $json = json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        File::put($outputPath, $json);

        $fileSize = strlen($json);
        $folderCount = count($folders);

        $this->info("  📂 Organized into {$folderCount} folders");
        $this->info("  💾 Saved to: {$outputPath}");
        $this->info("  📊 File size: " . number_format($fileSize) . " bytes");
        $this->info('');
        $this->info('  ✅ Postman Collection exported successfully.');
        $this->info('');

        return Command::SUCCESS;
    }

    /**
     * Determine the folder name for a given URI.
     */
    private function determineFolderName(string $uri): string
    {
        // Strip 'api/' and version prefix
        $path = preg_replace('#^api/(v\d+/)?#', '', $uri);
        $firstSegment = explode('/', $path)[0] ?? 'other';

        return $this->folderMap[$firstSegment] ?? ucfirst(str_replace('-', ' ', $firstSegment));
    }

    /**
     * Create a human-readable name from a URI path.
     */
    private function humanizePath(string $uri): string
    {
        // Strip 'api/' prefix for cleaner names
        $path = preg_replace('#^api/(v\d+/)?#', '', $uri);
        $parts = explode('/', $path);

        // Capitalize and clean up
        $parts = array_map(function ($part) {
            if (str_starts_with($part, '{')) {
                return $part; // Keep parameter placeholders
            }
            return ucfirst(str_replace('-', ' ', $part));
        }, $parts);

        return '/' . implode('/', $parts);
    }
}
