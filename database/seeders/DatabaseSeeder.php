<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\ApiKey;
use App\Models\ActivityLog;
use App\Models\SupportTicket;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. ROLES (5) ────────────────────────────────────────────────────
        $rolesData = [
            ['name' => 'System Overlord',   'slug' => 'admin',     'permissions' => ['*'],
             'description' => 'Supreme command-level authority over the entire cyber-grid.'],
            ['name' => 'Grid Moderator',    'slug' => 'moderator', 'permissions' => ['clients.*','products.*','orders.*','tickets.*'],
             'description' => 'Manages client portfolios and combat-asset catalogs.'],
            ['name' => 'Field Operative',   'slug' => 'operative', 'permissions' => ['orders.view','tickets.create'],
             'description' => 'Front-line agent executing contracts in hostile zones.'],
            ['name' => 'Corp Analyst',      'slug' => 'analyst',   'permissions' => ['analytics.*','reports.*'],
             'description' => 'Decodes financial intelligence and threat-pattern telemetry.'],
            ['name' => 'Client Node',       'slug' => 'client',    'permissions' => ['orders.own','invoices.own'],
             'description' => 'External corporate client with limited portal access.'],
        ];

        $roles = [];
        foreach ($rolesData as $r) {
            $roles[] = Role::create($r);
        }

        // ─── 2. USERS (10) ───────────────────────────────────────────────────
        $usersData = [
            ['name' => 'Zero-One',       'email' => 'zero.one@mercenaryking.net',   'role' => 0, 'status' => 'active'],
            ['name' => 'HexDec-7',       'email' => 'hexdec7@mercenaryking.net',    'role' => 1, 'status' => 'active'],
            ['name' => 'Kestrel-06',     'email' => 'kestrel06@mercenaryking.net',  'role' => 2, 'status' => 'active'],
            ['name' => 'Vex Phantom',    'email' => 'vex.phantom@mercenaryking.net','role' => 2, 'status' => 'active'],
            ['name' => 'Axiom Null',     'email' => 'axiom.null@mercenaryking.net', 'role' => 3, 'status' => 'active'],
            ['name' => 'Cypher-IX',      'email' => 'cypher9@mercenaryking.net',    'role' => 2, 'status' => 'inactive'],
            ['name' => 'Nyx Oracle',     'email' => 'nyx.oracle@mercenaryking.net', 'role' => 1, 'status' => 'active'],
            ['name' => 'Spectre-00',     'email' => 'spectre00@mercenaryking.net',  'role' => 2, 'status' => 'suspended'],
            ['name' => 'Data Wraith',    'email' => 'data.wraith@mercenaryking.net','role' => 3, 'status' => 'active'],
            ['name' => 'Iron Daemon',    'email' => 'iron.daemon@mercenaryking.net','role' => 4, 'status' => 'active'],
        ];

        $users = [];
        foreach ($usersData as $i => $u) {
            $users[] = User::create([
                'name'        => $u['name'],
                'email'       => $u['email'],
                'password'    => bcrypt('password'),
                'role_id'     => $roles[$u['role']]->id,
                'status'      => $u['status'],
                'avatar'      => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($u['name']),
                'last_active' => Carbon::now()->subMinutes(rand(1, 2880)),
            ]);
        }

        // ─── 3. CLIENTS (5) ──────────────────────────────────────────────────
        $clientsData = [
            ['name' => 'Arasaka Nexus Director',    'email' => 'ops@arasaka.corp',         'phone' => '+81-3-3581-2211',  'company' => 'Arasaka Corporation',     'status' => 'active',     'balance' => 9_580_400.00],
            ['name' => 'Militech Procurement Lead', 'email' => 'contracts@militech.us',    'phone' => '+1-703-555-0199',  'company' => 'Militech International',  'status' => 'active',     'balance' => 1_420_500.00],
            ['name' => 'Tyrell Liaison',            'email' => 'nexus@tyrell.bio',          'phone' => '+1-213-555-0108',  'company' => 'Tyrell Biotech',          'status' => 'active',     'balance' => 450_000.00],
            ['name' => 'Weyland-Yutani Colonel',    'email' => 'colonies@weyland.space',   'phone' => '+1-415-555-0142',  'company' => 'Weyland-Yutani Corp',     'status' => 'watch_list', 'balance' => -250_000.00],
            ['name' => 'OmniTech Shadow Broker',    'email' => 'shadow@omnitech.io',       'phone' => '+1-212-555-0177',  'company' => 'OmniTech Industries',     'status' => 'active',     'balance' => 780_000.00],
        ];

        $clients = [];
        foreach ($clientsData as $c) {
            $clients[] = Client::create($c);
        }

        // ─── 4. PRODUCTS (20) ────────────────────────────────────────────────
        $productsData = [
            // Cyberware
            ['name' => 'Sandevistan MK-IX Reflex Booster',   'sku' => 'CW-SND-09', 'price' => 18_500, 'stock' => 14,  'category' => 'Cyberware',  'description' => '+300% neural reaction velocity for 12s burst.'],
            ['name' => 'Mantis Blade Pro v2.1',               'sku' => 'CW-MTB-21', 'price' => 12_000, 'stock' => 22,  'category' => 'Cyberware',  'description' => 'Retractable carbon-nano blade implant for CQC.'],
            ['name' => 'Subdermal Armour Mk-4',               'sku' => 'CW-ARM-04', 'price' => 9_500,  'stock' => 31,  'category' => 'Cyberware',  'description' => 'Grade-4 subdermal impact plating.'],
            ['name' => 'Neural Jack EX-8',                    'sku' => 'CW-NJK-08', 'price' => 6_200,  'stock' => 50,  'category' => 'Cyberware',  'description' => 'High-bandwidth cortex-to-grid interface.'],
            // Software / AI
            ['name' => 'Kusanagi Daemon Injector v3X',        'sku' => 'SW-DMN-3X', 'price' => 4_200,  'stock' => 80,  'category' => 'Software',   'description' => 'Black-market netrunning daemon for ICE cracking.'],
            ['name' => 'Quantum Crypt-Key Decryptor',          'sku' => 'SW-QCK-01', 'price' => 29_500, 'stock' => 5,   'category' => 'Software',   'description' => 'Quantum-entanglement algo for corporate firewall breaches.'],
            ['name' => 'Phantom AI Combat Assistant',          'sku' => 'SW-PAI-01', 'price' => 15_000, 'stock' => 18,  'category' => 'AI Agent',   'description' => 'Autonomous battlefield threat-assessment module.'],
            ['name' => 'GridScan Surveillance Suite',          'sku' => 'SW-GSS-02', 'price' => 7_800,  'stock' => 25,  'category' => 'Software',   'description' => 'Full-spectrum passive drone surveillance firmware.'],
            // Hardware
            ['name' => 'Ares Sentinel Tactical HUD',           'sku' => 'HW-HUD-04', 'price' => 7_800,  'stock' => 22,  'category' => 'Hardware',   'description' => 'Real-time ocular telemetry synced to smart-ammo drones.'],
            ['name' => 'Arasaka Combat Cyberdeck v4',          'sku' => 'HW-CBD-V4', 'price' => 54_000, 'stock' => 8,   'category' => 'Hardware',   'description' => 'Grade-A military interface console for netrunners.'],
            ['name' => 'Militech Hardpoint Alpha',             'sku' => 'HW-HPT-AL', 'price' => 22_000, 'stock' => 12,  'category' => 'Hardware',   'description' => 'Modular shoulder-mounted weapons platform.'],
            ['name' => 'Neural Signal Jammer XP',              'sku' => 'HW-NSJ-XP', 'price' => 3_500,  'stock' => 40,  'category' => 'Hardware',   'description' => 'Area-denial EM pulse device disrupting all comms.'],
            // Weapons
            ['name' => 'Arasaka HLR-9 Thermal Rifle',         'sku' => 'WP-HLR-09', 'price' => 45_000, 'stock' => 0,   'category' => 'Weapons',    'description' => 'Heavy plasma infantry rifle with thermal tracking.'],
            ['name' => 'Nomad AMR-22 Anti-Material Rifle',     'sku' => 'WP-AMR-22', 'price' => 38_000, 'stock' => 3,   'category' => 'Weapons',    'description' => '2km-range depleted-uranium penetrator rounds.'],
            ['name' => 'Kang Tao G-58 Smart Pistol',           'sku' => 'WP-G58-KT', 'price' => 8_200,  'stock' => 60,  'category' => 'Weapons',    'description' => 'Auto-targeting micro-missile pistol.'],
            // Digital Assets
            ['name' => 'Dark Holonet Access Node License',     'sku' => 'DA-HOL-01', 'price' => 1_200,  'stock' => 999, 'category' => 'Digital',    'description' => 'Untraceable deep-web proxy node license.'],
            ['name' => 'Mercenary Certification Token MCT-7',  'sku' => 'DA-MCT-07', 'price' => 5_000,  'stock' => 999, 'category' => 'Digital',    'description' => 'On-chain mercenary rank credential.'],
            ['name' => 'Strike Contract NFT Pack (x10)',       'sku' => 'DA-SCN-10', 'price' => 25_000, 'stock' => 999, 'category' => 'Digital',    'description' => 'Tokenized combat mission contract batch.'],
            // Services
            ['name' => 'Rapid Extraction Protocol (REP)',      'sku' => 'SV-REP-01', 'price' => 80_000, 'stock' => 10,  'category' => 'Service',    'description' => 'Covert extraction from hostile-zone cities.'],
            ['name' => 'Corp Infiltration Consultancy',        'sku' => 'SV-CIC-01', 'price' => 120_000,'stock' => 5,   'category' => 'Service',    'description' => 'Full corporate espionage package with deniability clause.'],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $products[] = Product::create([
                'name'        => $p['name'],
                'price'       => $p['price'],
                'stock'       => $p['stock'],
                'type'        => $p['category'],
            ]);
        }

        // ─── 5. ORDERS (30) + ORDER ITEMS + INVOICES ─────────────────────────
        $providers  = ['NeuralPay', 'CryptoPay', 'Stripe', 'PayPal', 'BlockChain-X'];
        $orderCount = 30;
        $orders     = [];

        for ($i = 0; $i < $orderCount; $i++) {
            $minsAgo2  = rand(1440, 129600); // 1 day to 90 days in minutes
            $daysAgo   = (int)($minsAgo2 / 1440);
            $orderDate = Carbon::now()->subMinutes($minsAgo2);
            if ($orderDate->hour === 1 || $orderDate->hour === 2) {
                $orderDate->addHours(2);
            }
            $client    = $clients[array_rand($clients)];
            $numItems  = rand(1, 3);
            $total     = 0;
            $pickedProducts = array_rand($products, min($numItems, count($products)));
            if (!is_array($pickedProducts)) $pickedProducts = [$pickedProducts];

            // Determine status based on age
            $status    = $daysAgo < 3 ? 'pending' : (rand(0, 10) > 2 ? 'completed' : ($daysAgo < 7 ? 'processing' : 'cancelled'));
            $payStatus = $status === 'completed' ? 'paid' : ($status === 'cancelled' ? 'refunded' : 'unpaid');

            // Calculate total first
            foreach ($pickedProducts as $pi) {
                $total += $products[$pi]->price * rand(1, 2);
            }

            $order = Order::create([
                'client_id'      => $client->id,
                'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount'   => $total,
                'status'         => $status,
                'payment_status' => $payStatus,
                'created_at'     => $orderDate,
                'updated_at'     => $orderDate,
            ]);
            $orders[] = $order;

            // Order Items
            foreach ($pickedProducts as $pi) {
                $qty = rand(1, 2);
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $products[$pi]->id,
                    'quantity'   => $qty,
                    'price'      => $products[$pi]->price,
                    'created_at' => $orderDate,
                ]);
            }

            // Auto-generate Invoice
            Invoice::create([
                'order_id'       => $order->id,
                'invoice_number' => 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'issue_date'     => $orderDate->toDateString(),
                'due_date'       => (clone $orderDate)->addDays(14)->toDateString(),
                'status'         => $payStatus === 'paid' ? 'paid' : ($status === 'cancelled' ? 'void' : 'sent'),
                'created_at'     => $orderDate,
                'updated_at'     => $orderDate,
            ]);
        }

        // ─── 6. TRANSACTIONS (30) ────────────────────────────────────────────
        for ($i = 0; $i < 30; $i++) {
            $minsAgoTx   = rand(1440, 129600);
            $txDate      = Carbon::now()->subMinutes($minsAgoTx);
            if ($txDate->hour === 1 || $txDate->hour === 2) {
                $txDate->addHours(2);
            }
            $order       = $orders[array_rand($orders)];
            $client      = $clients[array_rand($clients)];
            $txStatus    = rand(0, 10) > 2 ? 'success' : (rand(0, 1) ? 'failed' : 'pending');

            Transaction::create([
                'order_id'        => $order->id,
                'client_id'       => $client->id,
                'transaction_ref' => 'TXN-' . strtoupper(Str::random(12)),
                'provider'        => $providers[array_rand($providers)],
                'amount'          => rand(1000, 150000) + (rand(0, 99) / 100),
                'status'          => $txStatus,
                'created_at'      => $txDate,
                'updated_at'      => $txDate,
            ]);
        }

        // ─── 7. API KEYS (3) ─────────────────────────────────────────────────
        ApiKey::create(['name' => 'Mobile Field Terminal',    'key' => 'mk_prod_fld_' . Str::random(18), 'status' => 'active',  'last_used_at' => Carbon::now()->subMinutes(12)]);
        ApiKey::create(['name' => 'Militech Satellite Feed',  'key' => 'mk_prod_sat_' . Str::random(18), 'status' => 'active',  'last_used_at' => Carbon::now()->subHours(2)]);
        ApiKey::create(['name' => 'Legacy Holonet Bridge',    'key' => 'mk_legacy_' . Str::random(20),   'status' => 'revoked', 'last_used_at' => Carbon::now()->subDays(15)]);

        // ─── 8. SUPPORT TICKETS ───────────────────────────────────────────────
        SupportTicket::create(['client_id' => $clients[0]->id, 'subject' => 'Implant Telemetry Lag in Sector 7',     'description' => '150ms activation delay on MK-IX Sandevistan. Suspected grid desync.', 'priority' => 'high',     'status' => 'in_progress', 'assigned_to' => $users[1]->id, 'created_at' => Carbon::now()->subHours(8)]);
        SupportTicket::create(['client_id' => $clients[3]->id, 'subject' => 'Billing Discrepancy – Orbit Transport', 'description' => 'Double-charged on last convoy escort to Orbit Station 4.',                'priority' => 'medium',   'status' => 'open',        'assigned_to' => null,        'created_at' => Carbon::now()->subDays(2)]);
        SupportTicket::create(['client_id' => $clients[1]->id, 'subject' => 'CRITICAL: Quantum Key Jammed',          'description' => 'Decryptors locked out by corporate counter-daemon. Need immediate rollback.', 'priority' => 'critical', 'status' => 'open',    'assigned_to' => $users[0]->id, 'created_at' => Carbon::now()->subMinutes(45)]);

        // ─── 9. SETTINGS ─────────────────────────────────────────────────────
        $settingsData = [
            ['key' => 'grid_status',          'value' => 'ONLINE',  'group' => 'general',   'description' => 'Current operating state of the platform.'],
            ['key' => 'cyber_defense_level',  'value' => 'SECURE',  'group' => 'security',  'description' => 'System firewall and neural-link defense state.'],
            ['key' => 'active_net_routes',    'value' => '142',     'group' => 'telemetry', 'description' => 'Multiplexed secure routing connections.'],
            ['key' => 'transaction_tax_rate', 'value' => '0.025',   'group' => 'payment',   'description' => 'Grid operational tax per transaction.'],
            ['key' => 'api_rate_limit',       'value' => '1000',    'group' => 'security',  'description' => 'Max requests per minute per API key.'],
            ['key' => 'app_version',          'value' => '3.5-AG',  'group' => 'general',   'description' => 'Current platform version.'],
        ];
        foreach ($settingsData as $s) {
            Setting::create($s);
        }

        // ─── 10. ACTIVITY LOGS (50) ───────────────────────────────────────────
        $actions = [
            ['action' => 'USER_LOGIN',           'desc' => 'Operator authenticated into the secure grid node.'],
            ['action' => 'ORDER_CREATE',         'desc' => 'New contract order injected into transaction ledger.'],
            ['action' => 'ORDER_COMPLETE',       'desc' => 'Contract execution confirmed – client notified.'],
            ['action' => 'PAYMENT_RECEIVED',     'desc' => 'Gateway cleared payment via NeuralPay mesh.'],
            ['action' => 'API_KEY_GENERATE',     'desc' => 'New secure API access node key provisioned.'],
            ['action' => 'API_KEY_REVOKE',       'desc' => 'Compromised API key de-authorized from network.'],
            ['action' => 'PRODUCT_CREATE',       'desc' => 'New asset specification uploaded to catalog.'],
            ['action' => 'PRODUCT_UPDATE',       'desc' => 'Asset specification configurations recalibrated.'],
            ['action' => 'CLIENT_REGISTER',      'desc' => 'New corporate client node initialized in registry.'],
            ['action' => 'SECURITY_LOCKDOWN',    'desc' => 'Emergency lockdown protocol activated on sector.'],
            ['action' => 'INVOICE_GENERATED',    'desc' => 'Holographic invoice rendered and transmitted.'],
            ['action' => 'TICKET_CREATED',       'desc' => 'Support channel request logged by operative.'],
            ['action' => 'TICKET_RESOLVED',      'desc' => 'Support channel closed – issue resolved.'],
            ['action' => 'SYSTEM_SCAN',          'desc' => 'Full matrix integrity scan completed – no anomalies.'],
            ['action' => 'SETTINGS_UPDATE',      'desc' => 'Primary grid configuration matrices recalibrated.'],
            ['action' => 'USER_CREATE',          'desc' => 'New operative node initialized in database registry.'],
            ['action' => 'TRANSACTION_FAILED',   'desc' => 'Gateway signal blocked – suspected intrusion detected.'],
            ['action' => 'BACKUP_COMPLETE',      'desc' => 'Encrypted grid snapshot archive committed.'],
        ];

        $ipPools = ['10.24.110.42', '192.168.10.15', '172.50.8.99', '127.0.0.1', '10.0.0.1', '192.168.1.1', '203.0.113.42'];

        for ($i = 0; $i < 50; $i++) {
            $action   = $actions[array_rand($actions)];
            $user     = $users[array_rand($users)];
            $minsAgo  = rand(1, 43200);

            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => $action['action'],
                'description' => $action['desc'],
                'ip_address'  => $ipPools[array_rand($ipPools)],
                'user_agent'  => 'CyberHUD/3.5 (MercenaryGrid; NeuralLink-OS)',
                'created_at'  => Carbon::now()->subMinutes($minsAgo),
                'updated_at'  => Carbon::now()->subMinutes($minsAgo),
            ]);
        }
    }
}
