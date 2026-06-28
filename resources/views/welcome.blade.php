<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MERCENARYKING-S // CORE INTERFACE v3.5-AG</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Share+Tech+Mono&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{cyber:['Orbitron','sans-serif'],mono:['Share Tech Mono','monospace'],sans:['Inter','sans-serif']}}}}
</script>
<style>
:root{--cyan:#06b6d4;--purple:#a855f7;--gold:#f59e0b;--pink:#ec4899;--green:#10b981;--dark:#050810;--card:rgba(8,14,30,0.72)}
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;background:var(--dark);color:#e2e8f0;font-family:'Inter',sans-serif;overflow:hidden}
#bg-canvas{position:fixed;inset:0;z-index:0;pointer-events:none}
body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background-image:linear-gradient(rgba(6,182,212,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(6,182,212,.04) 1px,transparent 1px);background-size:40px 40px}
body::after{content:'';position:fixed;inset:0;z-index:1;pointer-events:none;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.08) 2px,rgba(0,0,0,.08) 4px);animation:scanline 8s linear infinite}
@keyframes scanline{0%{background-position:0 0}100%{background-position:0 100%}}
.hud-scan{position:absolute;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(6,182,212,.6),transparent);animation:scan 5s linear infinite;pointer-events:none}
@keyframes scan{0%{top:0}100%{top:100%}}
.glass{background:var(--card);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(6,182,212,.15);border-radius:.75rem;box-shadow:0 8px 32px rgba(0,0,0,.4),inset 0 0 0 1px rgba(255,255,255,.03);transition:border-color .3s,box-shadow .3s,transform .3s;position:relative;overflow:hidden}
.glass:hover{border-color:rgba(6,182,212,.35);box-shadow:0 0 20px rgba(6,182,212,.12),0 8px 32px rgba(0,0,0,.5);transform:translateY(-1px)}
.glass-purple{border-color:rgba(168,85,247,.15)}.glass-purple:hover{border-color:rgba(168,85,247,.4);box-shadow:0 0 20px rgba(168,85,247,.12),0 8px 32px rgba(0,0,0,.5)}
.glass-gold{border-color:rgba(245,158,11,.15)}.glass-gold:hover{border-color:rgba(245,158,11,.4);box-shadow:0 0 20px rgba(245,158,11,.12),0 8px 32px rgba(0,0,0,.5)}
.neon-cyan{color:var(--cyan);text-shadow:0 0 8px rgba(6,182,212,.6)}
.neon-purple{color:var(--purple);text-shadow:0 0 8px rgba(168,85,247,.6)}
.neon-gold{color:var(--gold);text-shadow:0 0 8px rgba(245,158,11,.6)}
.neon-green{color:var(--green);text-shadow:0 0 8px rgba(16,185,129,.6)}
.neon-pink{color:var(--pink);text-shadow:0 0 8px rgba(236,72,153,.6)}
::-webkit-scrollbar{width:5px;height:5px}::-webkit-scrollbar-track{background:rgba(0,0,0,.3)}::-webkit-scrollbar-thumb{background:rgba(6,182,212,.3);border-radius:3px}
.nav-btn{display:flex;align-items:center;gap:.625rem;padding:.5rem .875rem;border-radius:.5rem;font-size:.75rem;font-family:'Orbitron',sans-serif;font-weight:600;letter-spacing:.05em;color:#64748b;border:1px solid transparent;cursor:pointer;transition:all .2s;width:100%;text-align:left}
.nav-btn:hover{color:#e2e8f0;background:rgba(6,182,212,.07);border-color:rgba(6,182,212,.2)}
.nav-btn.active{color:var(--cyan);background:rgba(6,182,212,.1);border-color:rgba(6,182,212,.3);text-shadow:0 0 8px rgba(6,182,212,.4)}
.nav-badge{font-size:9px;padding:1px 5px;border-radius:3px;border:1px solid;margin-left:auto;font-family:'Share Tech Mono',monospace}
.pulse-dot{width:7px;height:7px;border-radius:50%;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}
.float-deep{transform:perspective(1000px) rotateX(1deg);transform-style:preserve-3d}
.float-deep:hover{transform:perspective(1000px) rotateX(0deg) translateY(-2px)}
.cyber-input{background:rgba(8,14,30,.8);border:1px solid rgba(6,182,212,.2);border-radius:.375rem;color:#e2e8f0;font-family:'Share Tech Mono',monospace;font-size:.75rem;padding:.375rem .625rem;outline:none;width:100%;transition:border-color .2s}
.cyber-input:focus{border-color:var(--cyan);box-shadow:0 0 8px rgba(6,182,212,.2)}
.cyber-input:read-only{background:rgba(2,4,10,.9);color:#475569;cursor:not-allowed}
.btn{padding:.4rem 1rem;border-radius:.375rem;font-size:.7rem;font-family:'Orbitron',sans-serif;font-weight:700;letter-spacing:.05em;cursor:pointer;border:none;transition:all .2s;text-transform:uppercase}
.btn-cyan{background:var(--cyan);color:#050810;box-shadow:0 0 10px rgba(6,182,212,.3)}
.btn-cyan:hover{box-shadow:0 0 20px rgba(6,182,212,.6);filter:brightness(1.1)}
.btn-purple{background:var(--purple);color:#fff;box-shadow:0 0 10px rgba(168,85,247,.3)}
.btn-purple:hover{box-shadow:0 0 20px rgba(168,85,247,.6)}
.btn-gold{background:var(--gold);color:#050810;box-shadow:0 0 10px rgba(245,158,11,.3)}
.btn-gold:hover{box-shadow:0 0 20px rgba(245,158,11,.6)}
.btn-ghost{background:transparent;color:var(--cyan);border:1px solid rgba(6,182,212,.3)}
.btn-ghost:hover{background:rgba(6,182,212,.1);border-color:var(--cyan)}
.btn-danger{background:#ef4444;color:#fff;box-shadow:0 0 10px rgba(239,68,68,.3)}
.btn:disabled,.btn[disabled]{background:#1e293b!important;color:#475569!important;box-shadow:none!important;cursor:not-allowed!important;filter:none!important}
#toast-wrap{position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;max-width:22rem;pointer-events:none}
.toast{padding:.75rem 1rem;border-radius:.5rem;background:rgba(8,14,30,.95);border-left:3px solid var(--cyan);box-shadow:0 0 16px rgba(0,0,0,.5);font-size:.7rem;font-family:'Share Tech Mono',monospace;animation:toastIn .3s ease;pointer-events:auto}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
.cyber-table{width:100%;border-collapse:collapse;font-size:.7rem;font-family:'Share Tech Mono',monospace}
.cyber-table th{padding:.5rem .75rem;background:rgba(6,182,212,.06);color:#64748b;font-size:.65rem;letter-spacing:.08em;border-bottom:1px solid rgba(6,182,212,.12)}
.cyber-table td{padding:.5rem .75rem;border-bottom:1px solid rgba(255,255,255,.04);color:#94a3b8;vertical-align:middle}
.cyber-table tr:hover td{background:rgba(6,182,212,.04);color:#e2e8f0}
.badge{display:inline-flex;align-items:center;padding:1px 7px;border-radius:3px;font-size:.6rem;font-family:'Share Tech Mono',monospace;font-weight:700;border:1px solid;letter-spacing:.05em}
.badge-green{color:#10b981;border-color:#10b981;background:rgba(16,185,129,.1)}
.badge-red{color:#ef4444;border-color:#ef4444;background:rgba(239,68,68,.1)}
.badge-red-blink{color:#ef4444;border-color:#ef4444;background:rgba(239,68,68,.1);animation:blink 1.5s infinite}
.badge-yellow{color:#f59e0b;border-color:#f59e0b;background:rgba(245,158,11,.1)}
.badge-cyan{color:#06b6d4;border-color:#06b6d4;background:rgba(6,182,212,.1)}
.badge-purple{color:#a855f7;border-color:#a855f7;background:rgba(168,85,247,.1)}
.badge-slate{color:#64748b;border-color:#334155;background:rgba(51,65,85,.2)}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.5}}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(6px);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem}
.modal-box{width:100%;max-width:36rem;max-height:90vh;overflow-y:auto}
.json-terminal{background:#02040a;border:1px solid rgba(6,182,212,.2);border-radius:.5rem;padding:1rem;font-family:'Share Tech Mono',monospace;font-size:.68rem;color:#94a3b8;white-space:pre-wrap;word-break:break-all;min-height:120px;max-height:380px;overflow-y:auto;line-height:1.6}
.tab-panel{display:none}.tab-panel.active{display:flex;flex-direction:column;gap:.75rem}
@media print{body>*:not(#invoice-print-area){display:none}#invoice-print-area{position:absolute;left:0;top:0;width:100%;background:#fff;color:#000}}
</style>
</head>
<body class="dark">

<div id="toast-wrap"></div>
<canvas id="bg-canvas"></canvas>
<div id="invoice-print-area" style="display:none"></div>

<!-- ═══════════════════════════════════════════════════════════
     APP SHELL
     ═══════════════════════════════════════════════════════════ -->
<div class="relative z-10 flex h-screen overflow-hidden">

<!-- ╔════════════ SIDEBAR ════════════╗ -->
<aside class="flex flex-col w-56 flex-shrink-0 border-r border-cyan-500/10 bg-black/40 backdrop-blur-xl overflow-y-auto" style="min-height:100vh">
    <div class="p-4 border-b border-cyan-500/10">
        <div class="flex items-center gap-2.5 mb-1">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-cyan-500 to-purple-500 flex items-center justify-center font-cyber font-black text-sm text-black shadow-lg" style="animation:pulse 2s infinite">M</div>
            <div>
                <div class="font-cyber text-xs font-bold text-cyan-400" style="letter-spacing:.12em">MERCENARY</div>
                <div class="font-cyber text-xs font-bold text-purple-400" style="letter-spacing:.12em">KING-S</div>
            </div>
        </div>
        <div class="font-mono text-[9px] text-slate-600 pl-10">v3.5 // AG ENTERPRISE</div>
    </div>

    <!-- Telemetry mini bars -->
    <div class="px-3 py-2 border-b border-cyan-500/10 space-y-1.5">
        <div class="flex items-center justify-between font-mono text-[9px]">
            <span class="text-slate-600">CPU</span><span id="sb-cpu" class="neon-cyan">--</span>
        </div>
        <div class="w-full bg-slate-900 h-1 rounded overflow-hidden">
            <div id="sb-cpu-bar" class="h-full bg-cyan-500 rounded transition-all duration-1000" style="width:0%"></div>
        </div>
        <div class="flex items-center justify-between font-mono text-[9px]">
            <span class="text-slate-600">RAM</span><span id="sb-ram" class="neon-purple">--</span>
        </div>
        <div class="w-full bg-slate-900 h-1 rounded overflow-hidden">
            <div id="sb-ram-bar" class="h-full bg-purple-500 rounded transition-all duration-1000" style="width:0%"></div>
        </div>
        <div class="flex items-center justify-between font-mono text-[9px]">
            <span class="text-slate-600">LATENCY</span><span id="sb-lat" class="neon-gold">--</span>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 p-2 space-y-0.5 font-cyber text-xs">
        <button class="nav-btn active" data-tab="dashboard" onclick="switchTab('dashboard',this)"><i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>Dashboard</button>
        <button class="nav-btn" data-tab="users" onclick="switchTab('users',this)"><i data-lucide="users" class="w-3.5 h-3.5"></i>Users<span class="nav-badge" style="color:#06b6d4;border-color:#06b6d4" id="nb-users">...</span></button>
        <button class="nav-btn" data-tab="clients" onclick="switchTab('clients',this)"><i data-lucide="building-2" class="w-3.5 h-3.5"></i>Clients</button>
        <button class="nav-btn" data-tab="products" onclick="switchTab('products',this)"><i data-lucide="package" class="w-3.5 h-3.5"></i>Products</button>
        <button class="nav-btn" data-tab="orders" onclick="switchTab('orders',this)"><i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>Orders</button>
        <button class="nav-btn" data-tab="transactions" onclick="switchTab('transactions',this)"><i data-lucide="credit-card" class="w-3.5 h-3.5"></i>Transactions</button>
        <button class="nav-btn" data-tab="invoices" onclick="switchTab('invoices',this)"><i data-lucide="file-text" class="w-3.5 h-3.5"></i>Invoices</button>
        <button class="nav-btn" data-tab="analytics" onclick="switchTab('analytics',this)"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i>Analytics</button>
        <button class="nav-btn" data-tab="apikeys" onclick="switchTab('apikeys',this)"><i data-lucide="key" class="w-3.5 h-3.5"></i>API Center</button>
        <button class="nav-btn" data-tab="postman" onclick="switchTab('postman',this)"><i data-lucide="terminal" class="w-3.5 h-3.5"></i>Postman Tool</button>
        <button class="nav-btn" data-tab="logs" onclick="switchTab('logs',this)"><i data-lucide="activity" class="w-3.5 h-3.5"></i>Audit Logs</button>
        <button class="nav-btn" data-tab="tickets" onclick="switchTab('tickets',this)"><i data-lucide="headphones" class="w-3.5 h-3.5"></i>Tickets<span class="nav-badge badge-red-blink" id="nb-tickets">!</span></button>
        <button class="nav-btn" data-tab="settings" onclick="switchTab('settings',this)"><i data-lucide="settings" class="w-3.5 h-3.5"></i>Settings</button>
        <button class="nav-btn" data-tab="devops" onclick="switchTab('devops',this)"><i data-lucide="server" class="w-3.5 h-3.5"></i>DevOps Center</button>
    </nav>

    <!-- HUD footer -->
    <div class="p-3 border-t border-cyan-500/10 space-y-1 font-mono text-[9px]">
        <div class="flex items-center justify-between">
            <span class="text-slate-600">GRID</span>
            <span id="hd-grid" class="neon-green">ONLINE</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-slate-600">SHIELD</span>
            <span id="hd-shield" class="neon-cyan">SECURE</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-slate-600">ROUTES</span>
            <span id="hd-routes" class="neon-gold">--</span>
        </div>
    </div>
</aside>

<!-- ╔════════════ MAIN CONTENT ════════════╗ -->
<main class="flex-1 overflow-y-auto p-4 space-y-4">

    <!-- ████ 1. DASHBOARD ████ -->
    <div id="tab-dashboard" class="tab-panel active">
        <!-- HUD Header -->
        <div class="glass p-3 flex items-center justify-between" style="border-color:rgba(6,182,212,.25)">
            <div>
                <div class="font-cyber font-black text-base neon-cyan tracking-wider">MERCENARYKING-S</div>
                <div class="font-mono text-[9px] text-slate-500">ENTERPRISE CONTROL NODE // ANTI-GRAVITY PLATFORM v3.5-AG</div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 font-mono text-[10px]">
                    <div class="pulse-dot bg-green-500"></div>
                    <span class="text-green-400">GRID OPERATIONAL</span>
                </div>
                <div id="sys-badge" class="badge badge-green">● ONLINE</div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="glass p-4 float-deep">
                <div class="font-mono text-[9px] text-slate-500 mb-1">TOTAL OPERATIVES</div>
                <div id="m-users" class="font-cyber text-3xl font-black neon-cyan">--</div>
                <div class="font-mono text-[9px] text-slate-600 mt-1">Active grid agents</div>
            </div>
            <div class="glass glass-purple p-4 float-deep">
                <div class="font-mono text-[9px] text-slate-500 mb-1">CORP CLIENTS</div>
                <div id="m-clients" class="font-cyber text-3xl font-black neon-purple">--</div>
                <div class="font-mono text-[9px] text-slate-600 mt-1">Contracted entities</div>
            </div>
            <div class="glass glass-gold p-4 float-deep">
                <div class="font-mono text-[9px] text-slate-500 mb-1">REVENUE (TOTAL)</div>
                <div id="m-revenue" class="font-cyber text-2xl font-black neon-gold">--</div>
                <div class="font-mono text-[9px] text-slate-600 mt-1">All successful transactions</div>
            </div>
            <div class="glass p-4 float-deep" style="border-color:rgba(16,185,129,.2)">
                <div class="font-mono text-[9px] text-slate-500 mb-1">ORDERS / TICKETS</div>
                <div id="m-orders" class="font-cyber text-3xl font-black neon-green">--</div>
                <div id="m-pending" class="font-mono text-[9px] text-slate-600 mt-1">--</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="glass p-4 lg:col-span-2">
                <div class="font-mono text-[9px] text-cyan-400 tracking-widest mb-2">REVENUE TELEMETRY — 6 MONTHS</div>
                <div id="chart-revenue"></div>
            </div>
            <div class="glass p-4">
                <div class="font-mono text-[9px] text-purple-400 tracking-widest mb-2">ASSET CATEGORIES</div>
                <div id="chart-categories"></div>
            </div>
        </div>

        <!-- System Health + Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="glass p-4">
                <div class="font-mono text-[9px] text-yellow-400 tracking-widest mb-3">SYSTEM TELEMETRY</div>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div><div class="font-cyber text-xl neon-cyan" id="sys-cpu">--</div><div class="font-mono text-[9px] text-slate-600">CPU</div></div>
                    <div><div class="font-cyber text-xl neon-purple" id="sys-ram">--</div><div class="font-mono text-[9px] text-slate-600">RAM</div></div>
                    <div><div class="font-cyber text-xl neon-gold" id="sys-lat">--</div><div class="font-mono text-[9px] text-slate-600">LATENCY</div></div>
                    <div><div class="font-cyber text-lg neon-green" id="sys-uptime">--</div><div class="font-mono text-[9px] text-slate-600">NETWORK</div></div>
                    <div><div class="font-cyber text-lg neon-cyan" id="sys-defense">--</div><div class="font-mono text-[9px] text-slate-600">SHIELD</div></div>
                    <div><div class="font-cyber text-lg neon-gold" id="sys-routes">--</div><div class="font-mono text-[9px] text-slate-600">ROUTES</div></div>
                </div>
            </div>
            <div class="glass p-4 flex flex-col">
                <div class="font-mono text-[9px] text-green-400 tracking-widest mb-2">LIVE ACTIVITY FEED</div>
                <pre id="activity-feed" class="json-terminal flex-1 text-[9px]" style="min-height:140px">Initializing feed...</pre>
            </div>
        </div>
    </div>

    <!-- ████ 2. USERS ████ -->
    <div id="tab-users" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-cyan">OPERATIVE NODE REGISTRY</div>
                <button class="btn btn-cyan" onclick="openModal('user-modal')"><i data-lucide="plus" class="w-3.5 h-3.5 inline mr-1"></i>Add Operative</button>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>OPERATIVE</th><th>EMAIL NODE</th><th>ROLE</th><th>STATUS</th><th>LAST ACTIVE</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="users-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 3. CLIENTS ████ -->
    <div id="tab-clients" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-purple">CORP CLIENT REGISTRY</div>
                <button class="btn btn-purple" onclick="openModal('client-modal')"><i data-lucide="plus" class="w-3.5 h-3.5 inline mr-1"></i>Add Client</button>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>CORP</th><th>CONTACT</th><th>EMAIL</th><th>BALANCE</th><th>STATUS</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="clients-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 4. PRODUCTS ████ -->
    <div id="tab-products" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-gold">ASSET SPECIFICATION CATALOG</div>
                <button class="btn btn-gold" onclick="openModal('product-modal')"><i data-lucide="plus" class="w-3.5 h-3.5 inline mr-1"></i>Add Asset</button>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>ASSET</th><th>SKU</th><th>CATEGORY</th><th>PRICE</th><th>STOCK</th><th>STATUS</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="products-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 5. ORDERS ████ -->
    <div id="tab-orders" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-cyan">ORDER & CONTRACT LEDGER</div>
                <button class="btn btn-cyan" onclick="openModal('order-modal')"><i data-lucide="plus" class="w-3.5 h-3.5 inline mr-1"></i>New Order</button>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>ORDER#</th><th>CLIENT</th><th>ITEMS</th><th>TOTAL</th><th>STATUS</th><th>PAYMENT</th><th>DATE</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="orders-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 6. TRANSACTIONS ████ -->
    <div id="tab-transactions" class="tab-panel">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="glass p-4 flex flex-col gap-3 lg:col-span-1">
                <div class="font-cyber font-bold neon-purple">GATEWAY SIMULATION</div>
                <form onsubmit="simPayment(event)" class="flex flex-col gap-2">
                    <div><label class="font-mono text-[9px] text-slate-500">CLIENT</label>
                        <select id="tx-client" class="cyber-input mt-1"><option value="">-- select corp --</option></select></div>
                    <div><label class="font-mono text-[9px] text-slate-500">AMOUNT (USD)</label>
                        <input type="number" id="tx-amount" class="cyber-input mt-1" placeholder="15000" min="1" step="0.01"></div>
                    <div><label class="font-mono text-[9px] text-slate-500">PROVIDER</label>
                        <select id="tx-provider" class="cyber-input mt-1">
                            <option>NeuralPay</option><option>CryptoPay</option><option>Stripe</option><option>PayPal</option><option>BlockChain-X</option>
                        </select></div>
                    <div><label class="font-mono text-[9px] text-slate-500">STATUS</label>
                        <select id="tx-status" class="cyber-input mt-1">
                            <option>success</option><option>failed</option><option>pending</option>
                        </select></div>
                    <div class="font-mono text-[10px] text-slate-500">TOTAL VOLUME (SUCCESS): <span id="tx-total-vol" class="neon-gold">$0.00</span></div>
                    <button type="submit" class="btn btn-purple">TRANSMIT PAYMENT</button>
                </form>
            </div>
            <div class="glass p-4 lg:col-span-2 flex flex-col gap-3">
                <div class="font-cyber font-bold neon-purple">TRANSACTION LEDGER</div>
                <div class="overflow-x-auto">
                    <table class="cyber-table">
                        <thead><tr><th>REF</th><th>CORP</th><th>PROVIDER</th><th>AMOUNT</th><th>STATUS</th><th>DATE</th></tr></thead>
                        <tbody id="tx-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ████ 7. INVOICES ████ -->
    <div id="tab-invoices" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="font-cyber font-bold neon-gold">INVOICE CONTROL CENTER</div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>INVOICE#</th><th>ORDER</th><th>CLIENT</th><th>AMOUNT</th><th>ISSUED</th><th>DUE</th><th>STATUS</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="invoices-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 8. ANALYTICS ████ -->
    <div id="tab-analytics" class="tab-panel">
        <div class="grid grid-cols-3 gap-3 mb-3">
            <div class="glass p-3 text-center">
                <div class="font-mono text-[9px] text-slate-500">TOTAL REVENUE</div>
                <div id="ana-revenue" class="font-cyber text-2xl neon-gold mt-1">--</div>
            </div>
            <div class="glass p-3 text-center">
                <div class="font-mono text-[9px] text-slate-500">TOTAL ORDERS</div>
                <div id="ana-orders" class="font-cyber text-2xl neon-cyan mt-1">--</div>
            </div>
            <div class="glass p-3 text-center">
                <div class="font-mono text-[9px] text-slate-500">LOGS (LOADED)</div>
                <div id="ana-logs24" class="font-cyber text-2xl neon-purple mt-1">--</div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="glass p-4">
                <div class="font-mono text-[9px] text-cyan-400 mb-2">MONTHLY REVENUE</div>
                <div id="chart-ana-rev"></div>
            </div>
            <div class="glass p-4">
                <div class="font-mono text-[9px] text-purple-400 mb-2">GATEWAY PROVIDER BREAKDOWN</div>
                <div id="chart-providers"></div>
            </div>
        </div>
    </div>

    <!-- ████ 9. API CENTER ████ -->
    <div id="tab-apikeys" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-cyan">API NODE CONTROL CENTER</div>
                <div class="flex gap-2">
                    <button class="btn btn-ghost" onclick="simulateLatency()"><i data-lucide="activity" class="w-3.5 h-3.5 inline mr-1"></i>Run Latency Test</button>
                    <button class="btn btn-cyan" onclick="generateKey()"><i data-lucide="key" class="w-3.5 h-3.5 inline mr-1"></i>Generate Key</button>
                </div>
            </div>
            <!-- Endpoint listing -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 text-[9px] font-mono border-b border-slate-800 pb-4">
                <div class="font-cyber text-[9px] text-yellow-400 col-span-full mb-1 tracking-widest">REGISTERED API ENDPOINTS — /api/</div>
                @php
                $endpoints=[
                    ['GET','dashboard/stats'],
                    ['GET','users'],['POST','users'],['PUT','users/{id}'],['DELETE','users/{id}'],
                    ['GET','clients'],['POST','clients'],['PUT','clients/{id}'],['DELETE','clients/{id}'],
                    ['GET','products'],['POST','products'],['PUT','products/{id}'],['DELETE','products/{id}'],
                    ['GET','orders'],['POST','orders'],['PUT','orders/{id}/status'],
                    ['GET','transactions'],['POST','transactions/simulate'],
                    ['GET','invoices'],['GET','invoices/{id}'],['PUT','invoices/{id}/status'],
                    ['GET','api-keys'],['POST','api-keys'],['PUT','api-keys/{id}/revoke'],
                    ['GET','activity-logs'],
                    ['GET','tickets'],['POST','tickets'],['PUT','tickets/{id}'],['DELETE','tickets/{id}'],
                    ['GET','settings'],['PUT','settings'],
                    ['GET','postman/export'],
                ];
                @endphp
                @foreach($endpoints as $ep)
                <div class="flex items-center gap-1.5 p-1.5 rounded border border-slate-800 hover:border-yellow-500/30 transition-colors">
                    <span class="badge @if($ep[0]==='GET') badge-cyan @elseif($ep[0]==='POST') badge-green @elseif($ep[0]==='PUT') badge-yellow @else badge-red @endif" style="font-size:8px">{{ $ep[0] }}</span>
                    <span class="text-slate-400 truncate">/{{ $ep[1] }}</span>
                </div>
                @endforeach
            </div>
            <!-- API Keys table -->
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>KEY NAME</th><th>CRYPTO NODE</th><th>STATUS</th><th>LAST HANDSHAKE</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="apikeys-tbody"></tbody>
                </table>
            </div>
            <!-- Latency display -->
            <div id="latency-display" class="hidden border-t border-slate-800 pt-3">
                <div class="font-mono text-[10px] text-slate-400 mb-2">LATENCY SIMULATION RESULTS:</div>
                <div id="latency-bars" class="flex flex-col gap-1"></div>
            </div>
        </div>
    </div>

    <!-- ████ 10. POSTMAN TOOL ████ -->
    <div id="tab-postman" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4" style="border-color:rgba(6,182,212,.3)">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-cyan-500/10 pb-4">
                <div>
                    <div class="font-cyber font-bold text-transparent bg-clip-text" style="background:linear-gradient(90deg,#06b6d4,#a855f7);-webkit-background-clip:text">STRICT API CONTROL TERMINAL</div>
                    <div class="font-mono text-[10px] text-slate-500 mt-0.5">Enterprise-grade request validation — invalid calls are blocked before transmission.</div>
                </div>
                <a href="/api/postman/export" class="btn btn-cyan text-center flex items-center gap-2"><i data-lucide="download" class="w-3.5 h-3.5"></i>Export Collection</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <!-- Request builder LEFT -->
                <div class="lg:col-span-2 flex flex-col gap-3">
                    <div class="flex items-center justify-between font-mono text-[9px] border-b border-slate-800 pb-1">
                        <span class="text-cyan-400 tracking-widest">REQUEST BUILDER</span>
                        <span id="pm-validation-badge" class="badge badge-slate">UNCHECKED</span>
                    </div>

                    <!-- Endpoint selector -->
                    <div>
                        <label class="font-mono text-[9px] text-slate-500 uppercase">Endpoint</label>
                        <select id="pm-route" class="cyber-input mt-1" onchange="onEndpointChange()">
                            <option value="/api/dashboard/stats" data-method="GET">GET /api/dashboard/stats</option>
                            <option value="/api/users" data-method="POST">POST /api/users</option>
                            <option value="/api/users" data-method="GET">GET /api/users</option>
                            <option value="/api/users/{id}" data-method="GET">GET /api/users/{id}</option>
                            <option value="/api/users/{id}" data-method="PUT">PUT /api/users/{id}</option>
                            <option value="/api/users/{id}" data-method="DELETE">DELETE /api/users/{id}</option>
                            <option value="/api/clients" data-method="POST">POST /api/clients</option>
                            <option value="/api/clients" data-method="GET">GET /api/clients</option>
                            <option value="/api/clients/{id}" data-method="PUT">PUT /api/clients/{id}</option>
                            <option value="/api/clients/{id}" data-method="DELETE">DELETE /api/clients/{id}</option>
                            <option value="/api/products" data-method="POST">POST /api/products</option>
                            <option value="/api/products" data-method="GET">GET /api/products</option>
                            <option value="/api/products/{id}" data-method="PUT">PUT /api/products/{id}</option>
                            <option value="/api/products/{id}" data-method="DELETE">DELETE /api/products/{id}</option>
                            <option value="/api/orders" data-method="POST">POST /api/orders</option>
                            <option value="/api/orders" data-method="GET">GET /api/orders</option>
                            <option value="/api/orders/{id}/status" data-method="PUT">PUT /api/orders/{id}/status</option>
                            <option value="/api/transactions" data-method="GET">GET /api/transactions</option>
                            <option value="/api/transactions/simulate" data-method="POST">POST /api/transactions/simulate</option>
                            <option value="/api/invoices" data-method="GET">GET /api/invoices</option>
                            <option value="/api/invoices/{id}" data-method="GET">GET /api/invoices/{id}</option>
                            <option value="/api/invoices/{id}/status" data-method="PUT">PUT /api/invoices/{id}/status</option>
                            <option value="/api/api-keys" data-method="POST">POST /api/api-keys</option>
                            <option value="/api/api-keys" data-method="GET">GET /api/api-keys</option>
                            <option value="/api/api-keys/{id}/revoke" data-method="PUT">PUT /api/api-keys/{id}/revoke</option>
                            <option value="/api/activity-logs" data-method="GET">GET /api/activity-logs</option>
                            <option value="/api/tickets" data-method="POST">POST /api/tickets</option>
                            <option value="/api/tickets" data-method="GET">GET /api/tickets</option>
                            <option value="/api/tickets/{id}" data-method="PUT">PUT /api/tickets/{id}</option>
                            <option value="/api/tickets/{id}" data-method="DELETE">DELETE /api/tickets/{id}</option>
                            <option value="/api/settings" data-method="PUT">PUT /api/settings</option>
                            <option value="/api/settings" data-method="GET">GET /api/settings</option>
                            <option value="/api/postman/export" data-method="GET">GET /api/postman/export</option>
                        </select>
                    </div>

                    <!-- Route param -->
                    <div id="pm-param-wrap" class="hidden flex-col gap-1">
                        <label class="font-mono text-[9px] text-slate-500 uppercase">Route {id} Parameter</label>
                        <input type="text" id="pm-param-id" class="cyber-input mt-1" placeholder="e.g. 1" value="1" oninput="updateTargetPath(); validateRequest()">
                    </div>

                    <!-- Manual override toggle -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="pm-custom-enable" class="accent-cyan-500" onchange="toggleCustomPath()">
                        <label for="pm-custom-enable" class="font-mono text-[9px] text-slate-400 cursor-pointer">Manual Path Override</label>
                    </div>

                    <!-- Final target path (readonly unless override enabled) -->
                    <div>
                        <label class="font-mono text-[9px] text-slate-500 uppercase">Target Request Path</label>
                        <input type="text" id="pm-custom" class="cyber-input mt-1" placeholder="/api/users/1" readonly oninput="validateRequest()">
                    </div>

                    <!-- HTTP Method (auto-set, can change for override) -->
                    <div>
                        <label class="font-mono text-[9px] text-slate-500 uppercase">HTTP Method</label>
                        <select id="pm-method" class="cyber-input mt-1" onchange="onMethodChange()">
                            <option>GET</option><option>POST</option><option>PUT</option><option>DELETE</option>
                        </select>
                    </div>

                    <!-- Body (shown for POST/PUT) -->
                    <div id="pm-body-wrap" class="hidden flex-col gap-1">
                        <div class="flex items-center justify-between">
                            <label class="font-mono text-[9px] text-slate-500 uppercase">JSON Payload</label>
                            <button type="button" class="font-mono text-[8px] text-yellow-500 hover:text-yellow-400" onclick="resetTemplate()">[RESET TEMPLATE]</button>
                        </div>
                        <textarea id="pm-body" class="cyber-input mt-1" rows="7" placeholder="{}" oninput="validateRequest()"></textarea>
                    </div>

                    <!-- Validation errors -->
                    <div id="pm-errors" class="hidden p-2.5 rounded border border-red-500/30 bg-red-950/20 text-red-400 font-mono text-[9px] flex flex-col gap-0.5" style="box-shadow:0 0 12px rgba(239,68,68,.15)"></div>

                    <button id="pm-exec-btn" onclick="execRequest()" class="btn btn-cyan w-full flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>Execute Request
                    </button>
                </div>

                <!-- Response viewer RIGHT -->
                <div class="lg:col-span-3 flex flex-col gap-3">
                    <div class="flex items-center justify-between font-mono text-[9px] border-b border-slate-800 pb-1">
                        <span class="text-pink-400 tracking-widest">RESPONSE TERMINAL</span>
                        <span id="pm-status-badge" class="badge badge-slate">IDLE</span>
                    </div>
                    <div class="flex items-center gap-4 font-mono text-[10px]">
                        <span class="text-slate-500">HTTP:</span><span id="pm-http-code" class="neon-cyan font-bold text-lg">---</span>
                        <span class="text-slate-500 ml-2">TIME:</span><span id="pm-time" class="neon-gold">-- ms</span>
                        <span class="text-slate-500 ml-2">SIZE:</span><span id="pm-size" class="neon-purple">-- B</span>
                    </div>
                    <pre id="pm-output" class="json-terminal flex-1" style="min-height:320px">Awaiting transmission...</pre>
                </div>
            </div>
        </div>
    </div>

    <!-- ████ 11. AUDIT LOGS ████ -->
    <div id="tab-logs" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-cyan">SYSTEM AUDIT TRAIL</div>
                <button class="btn btn-ghost" onclick="loadLogs()"><i data-lucide="refresh-cw" class="w-3.5 h-3.5 inline mr-1"></i>Refresh</button>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>TIMESTAMP</th><th>OPERATOR</th><th>ACTION</th><th>DESCRIPTION</th><th>IP ORIGIN</th></tr></thead>
                    <tbody id="logs-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 12. TICKETS ████ -->
    <div id="tab-tickets" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="font-cyber font-bold neon-pink">SUPPORT CHANNELS</div>
                <button class="btn btn-danger" onclick="openModal('ticket-modal')"><i data-lucide="plus" class="w-3.5 h-3.5 inline mr-1"></i>Open Channel</button>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>#</th><th>CORP</th><th>SUBJECT</th><th>PRIORITY</th><th>STATUS</th><th class="text-right">CMD</th></tr></thead>
                    <tbody id="tickets-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ████ 13. SETTINGS ████ -->
    <div id="tab-settings" class="tab-panel">
        <div class="glass p-4 flex flex-col gap-4">
            <div class="font-cyber font-bold neon-gold">MATRIX CONFIGURATION</div>
            <form onsubmit="saveSettings(event)" class="flex flex-col gap-4">
                <div id="settings-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                <button type="submit" class="btn btn-gold self-start">CALIBRATE GRID MATRIX</button>
            </form>
        </div>
    </div>

    <!-- ████ 14. DEVOPS CONTROL CENTER ████ -->
    <div id="tab-devops" class="tab-panel">
        <div class="glass p-4 mb-4 flex items-center justify-between" style="border-color:rgba(168,85,247,.25)">
            <div>
                <div class="font-cyber font-black text-base neon-purple tracking-wider">DEVOPS CONTROL CENTER</div>
                <div class="font-mono text-[9px] text-slate-500">CI/CD PIPELINE & DEPLOYMENT TELEMETRY</div>
            </div>
            <div class="flex items-center gap-3">
                <button class="btn btn-ghost" onclick="loadDevops()"><i data-lucide="refresh-cw" class="w-3 h-3 inline mr-1"></i>RE-SCAN</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <!-- Pipeline Status -->
            <div class="glass p-4 flex flex-col gap-2">
                <div class="font-mono text-[9px] text-cyan-400 tracking-widest mb-1">PIPELINE STATUS</div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">STATE</span>
                    <span id="do-pl-status" class="badge badge-slate">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">LAST RUN</span>
                    <span id="do-pl-run" class="text-[10px] font-mono text-slate-300">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">DURATION</span>
                    <span id="do-pl-dur" class="text-[10px] font-mono text-slate-300">--</span>
                </div>
            </div>

            <!-- Deployment Status -->
            <div class="glass glass-gold p-4 flex flex-col gap-2">
                <div class="font-mono text-[9px] text-gold tracking-widest neon-gold mb-1">DEPLOYMENT TARGET</div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">STATE</span>
                    <span id="do-dp-status" class="badge badge-slate">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">ENV</span>
                    <span id="do-dp-env" class="text-[10px] font-mono text-slate-300">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">LAST DEPLOY</span>
                    <span id="do-dp-time" class="text-[10px] font-mono text-slate-300">--</span>
                </div>
            </div>

            <!-- Git & Postman -->
            <div class="glass glass-purple p-4 flex flex-col gap-2">
                <div class="font-mono text-[9px] text-purple-400 tracking-widest mb-1">SOURCE CONTROL & DOCS</div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">COMMIT</span>
                    <span id="do-git-hash" class="text-[10px] font-mono text-cyan-300">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">BRANCH</span>
                    <span id="do-git-branch" class="text-[10px] font-mono text-slate-300">--</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-cyber text-slate-400">POSTMAN SYNC</span>
                    <span id="do-pm-sync" class="badge badge-slate">--</span>
                </div>
            </div>
        </div>

        <!-- API Health Matrix -->
        <div class="glass p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="font-mono text-[9px] text-cyan-400 tracking-widest">API HEALTH MATRIX</div>
                <span id="do-api-overall" class="badge badge-slate">CHECKING...</span>
            </div>
            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead><tr><th>ENDPOINT</th><th>METHOD</th><th>STATUS</th><th>CODE</th><th>NOTES</th></tr></thead>
                    <tbody id="do-api-tbody">
                        <tr><td colspan="5" class="text-center py-4">Awaiting telemetry...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main><!-- end main -->
</div><!-- end shell -->

<!-- ═══════════ MODALS ═══════════ -->

<!-- User Modal -->
<div id="user-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeModal('user-modal')">
    <div class="modal-box glass p-5">
        <div class="font-cyber font-bold neon-cyan mb-4">NEW OPERATIVE</div>
        <form onsubmit="createUser(event)" class="flex flex-col gap-3">
            <div class="grid grid-cols-2 gap-3">
                <div><label class="font-mono text-[9px] text-slate-500">OPERATIVE NAME</label><input id="u-name" class="cyber-input mt-1" placeholder="Kestrel-09" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">EMAIL NODE</label><input id="u-email" type="email" class="cyber-input mt-1" placeholder="k9@mk.net" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">ACCESS CODE</label><input id="u-pass" type="password" class="cyber-input mt-1" placeholder="min 6 chars" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">STATUS</label>
                    <select id="u-status" class="cyber-input mt-1"><option>active</option><option>inactive</option><option>suspended</option></select></div>
            </div>
            <div class="flex gap-2 justify-end mt-2">
                <button type="button" class="btn btn-ghost" onclick="closeModal('user-modal')">Cancel</button>
                <button type="submit" class="btn btn-cyan">Initialize</button>
            </div>
        </form>
    </div>
</div>

<!-- Client Modal -->
<div id="client-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeModal('client-modal')">
    <div class="modal-box glass p-5">
        <div class="font-cyber font-bold neon-purple mb-4">REGISTER CORP CLIENT</div>
        <form onsubmit="createClient(event)" class="flex flex-col gap-3">
            <div class="grid grid-cols-2 gap-3">
                <div><label class="font-mono text-[9px] text-slate-500">CONTACT NAME</label><input id="cl-name" class="cyber-input mt-1" placeholder="V. Arasaka" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">CORPORATION</label><input id="cl-company" class="cyber-input mt-1" placeholder="Arasaka Corp" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">EMAIL</label><input id="cl-email" type="email" class="cyber-input mt-1" placeholder="ceo@arasaka.jp" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">PHONE</label><input id="cl-phone" class="cyber-input mt-1" placeholder="+1-800-000-0000"></div>
                <div><label class="font-mono text-[9px] text-slate-500">BALANCE (USD)</label><input id="cl-balance" type="number" class="cyber-input mt-1" placeholder="500000" value="0" step="0.01"></div>
                <div><label class="font-mono text-[9px] text-slate-500">STATUS</label>
                    <select id="cl-status" class="cyber-input mt-1"><option>active</option><option>inactive</option><option>watch_list</option></select></div>
            </div>
            <div class="flex gap-2 justify-end mt-2">
                <button type="button" class="btn btn-ghost" onclick="closeModal('client-modal')">Cancel</button>
                <button type="submit" class="btn btn-purple">Register</button>
            </div>
        </form>
    </div>
</div>

<!-- Product Modal -->
<div id="product-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeModal('product-modal')">
    <div class="modal-box glass p-5">
        <div class="font-cyber font-bold neon-gold mb-4">UPLOAD ASSET SPECIFICATION</div>
        <form onsubmit="createProduct(event)" class="flex flex-col gap-3">
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2"><label class="font-mono text-[9px] text-slate-500">ASSET NAME</label><input id="pr-name" class="cyber-input mt-1" placeholder="Quantum Crypt-Key Decryptor" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">TYPE</label>
                <select id="pr-type" class="cyber-input mt-1">
                    <option value="Cyberware">Cyberware</option>
                    <option value="Software">Software</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Weapons">Weapons</option>
                    <option value="Digital">Digital</option>
                    <option value="Service">Service</option>
                    <option value="AI Agent">AI Agent</option>
                </select></div>
                <div><label class="font-mono text-[9px] text-slate-500">PRICE (USD)</label><input id="pr-price" type="number" class="cyber-input mt-1" placeholder="9500" min="0" step="0.01" required></div>
                <div><label class="font-mono text-[9px] text-slate-500">STOCK UNITS</label><input id="pr-stock" type="number" class="cyber-input mt-1" placeholder="20" min="0" required></div>
            </div>
            <div class="flex gap-2 justify-end mt-2">
                <button type="button" class="btn btn-ghost" onclick="closeModal('product-modal')">Cancel</button>
                <button type="submit" class="btn btn-gold">Upload Asset</button>
            </div>
        </form>
    </div>
</div>

<!-- Order Modal -->
<div id="order-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeModal('order-modal')">
    <div class="modal-box glass p-5">
        <div class="font-cyber font-bold neon-cyan mb-4">INITIATE CONTRACT ORDER</div>
        <form onsubmit="createOrder(event)" class="flex flex-col gap-3">
            <div><label class="font-mono text-[9px] text-slate-500">CLIENT</label>
                <select id="ord-client" class="cyber-input mt-1"><option value="">-- select corp --</option></select></div>
            <div><label class="font-mono text-[9px] text-slate-500">PRODUCT (single line item)</label>
                <select id="ord-product" class="cyber-input mt-1"><option value="">-- select asset --</option></select></div>
            <div><label class="font-mono text-[9px] text-slate-500">QUANTITY</label>
                <input id="ord-qty" type="number" class="cyber-input mt-1" value="1" min="1" max="99"></div>
            <div><label class="font-mono text-[9px] text-slate-500">GATEWAY</label>
                <select id="ord-gateway" class="cyber-input mt-1"><option>NeuralPay</option><option>CryptoPay</option><option>Stripe</option><option>PayPal</option><option>BlockChain-X</option></select></div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="ord-sim" class="accent-cyan-500" checked>
                <label for="ord-sim" class="font-mono text-[9px] text-slate-400">Simulate payment immediately</label>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" class="btn btn-ghost" onclick="closeModal('order-modal')">Cancel</button>
                <button type="submit" class="btn btn-cyan">Transmit Order</button>
            </div>
        </form>
    </div>
</div>

<!-- Ticket Modal -->
<div id="ticket-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeModal('ticket-modal')">
    <div class="modal-box glass p-5">
        <div class="font-cyber font-bold neon-pink mb-4">OPEN SUPPORT CHANNEL</div>
        <form onsubmit="submitTicket(event)" class="flex flex-col gap-3">
            <div><label class="font-mono text-[9px] text-slate-500">CLIENT</label>
                <select id="tk-client" class="cyber-input mt-1"><option value="">-- select corp --</option></select></div>
            <div><label class="font-mono text-[9px] text-slate-500">SUBJECT</label>
                <input id="tk-subject" class="cyber-input mt-1" placeholder="CRITICAL: Quantum Key Jammed" required></div>
            <div><label class="font-mono text-[9px] text-slate-500">DESCRIPTION</label>
                <textarea id="tk-desc" class="cyber-input mt-1" rows="3" placeholder="Detailed incident description..." required></textarea></div>
            <div><label class="font-mono text-[9px] text-slate-500">PRIORITY</label>
                <select id="tk-priority" class="cyber-input mt-1"><option>low</option><option>medium</option><option selected>high</option><option>critical</option></select></div>
            <div class="flex gap-2 justify-end">
                <button type="button" class="btn btn-ghost" onclick="closeModal('ticket-modal')">Cancel</button>
                <button type="submit" class="btn btn-danger">Open Channel</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     JAVASCRIPT
     ═══════════════════════════════════════════════════════════════ -->
<script>
// ── Globals ──────────────────────────────────────────────────────────────────
const API  = '/api';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let cachedClients = [];
let cachedProducts = [];
let chartRev = null, chartCat = null, chartAnaRev = null, chartProv = null;

// ── Particles ─────────────────────────────────────────────────────────────────
(function(){
    const cv = document.getElementById('bg-canvas');
    const cx = cv.getContext('2d');
    cv.width = window.innerWidth; cv.height = window.innerHeight;
    window.addEventListener('resize', () => { cv.width = window.innerWidth; cv.height = window.innerHeight; });
    const pts = Array.from({length:60}, () => ({
        x:Math.random()*cv.width, y:Math.random()*cv.height,
        vx:(Math.random()-.5)*.4, vy:(Math.random()-.5)*.4, r:Math.random()*1.5+.5
    }));
    function draw(){
        cx.clearRect(0,0,cv.width,cv.height);
        pts.forEach(p => {
            p.x+=p.vx; p.y+=p.vy;
            if(p.x<0||p.x>cv.width)p.vx*=-1;
            if(p.y<0||p.y>cv.height)p.vy*=-1;
            cx.beginPath(); cx.arc(p.x,p.y,p.r,0,Math.PI*2);
            cx.fillStyle='rgba(6,182,212,.35)'; cx.fill();
        });
        pts.forEach((a,i) => pts.slice(i+1).forEach(b => {
            const d=Math.hypot(a.x-b.x,a.y-b.y);
            if(d<120){cx.beginPath();cx.moveTo(a.x,a.y);cx.lineTo(b.x,b.y);cx.strokeStyle=`rgba(6,182,212,${.15*(1-d/120)})`;cx.lineWidth=.5;cx.stroke();}
        }));
        requestAnimationFrame(draw);
    }
    draw();
})();

// ── Fetch helper ─────────────────────────────────────────────────────────────
async function api(url, opts = {}) {
    const res = await fetch(url, {
        ...opts,
        headers: { 'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':CSRF, ...(opts.headers||{}) },
        body: opts.body ? JSON.stringify(opts.body) : undefined
    });
    const text = await res.text();
    if (!text.trim()) return {};
    try { return JSON.parse(text); }
    catch(e) {
        if (text.trim().startsWith('<')) throw new Error('Server returned HTML — check API routing & middleware.');
        throw new Error('Invalid JSON response: ' + e.message);
    }
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function toast(msg, color='cyan') {
    const t = document.createElement('div');
    const colors = {cyan:'#06b6d4',green:'#10b981',red:'#ef4444',yellow:'#f59e0b',purple:'#a855f7'};
    t.className='toast'; t.style.borderLeftColor = colors[color]||colors.cyan;
    t.textContent = msg;
    document.getElementById('toast-wrap').appendChild(t);
    setTimeout(() => t.remove(), 4000);
}

// ── Modal ─────────────────────────────────────────────────────────────────────
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// ── Tab Switcher ──────────────────────────────────────────────────────────────
const TAB_LOADERS = {
    users:        loadUsers,
    clients:      loadClients,
    products:     loadProducts,
    orders:       loadOrders,
    transactions: loadTransactions,
    invoices:     loadInvoices,
    analytics:    loadAnalytics,
    apikeys:      loadApiKeys,
    logs:         loadLogs,
    tickets:      loadTickets,
    settings:     loadSettings,
    devops:       loadDevops,
};
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+name).classList.add('active');
    btn.classList.add('active');
    if (TAB_LOADERS[name]) TAB_LOADERS[name]();
    setTimeout(() => lucide.createIcons(), 50);
}

// ══════════════════════════════════════════════════════════════════════════════
// 1. DASHBOARD
// ══════════════════════════════════════════════════════════════════════════════
async function loadDashboard() {
    try {
        const res = await api(`${API}/dashboard/stats`);
        const m = res.metrics;
        document.getElementById('m-users').textContent   = m.users;
        document.getElementById('m-clients').textContent = m.clients;
        document.getElementById('m-orders').textContent  = m.orders;
        document.getElementById('m-pending').textContent = m.active_tickets + ' active tickets';
        document.getElementById('m-revenue').textContent = '$' + parseFloat(m.revenue).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});

        const t = res.telemetry;
        document.getElementById('hd-grid').textContent    = t.grid_status;
        document.getElementById('hd-shield').textContent  = t.defense_level;
        document.getElementById('hd-routes').textContent  = t.active_routes;
        document.getElementById('sb-cpu').textContent     = t.cpu_load+'%';
        document.getElementById('sb-cpu-bar').style.width = t.cpu_load+'%';
        document.getElementById('sb-ram').textContent     = t.ram_usage+'%';
        document.getElementById('sb-ram-bar').style.width = t.ram_usage+'%';
        document.getElementById('sb-lat').textContent     = t.api_latency+' ms';
        document.getElementById('sys-cpu').textContent    = t.cpu_load+'%';
        document.getElementById('sys-ram').textContent    = t.ram_usage+'%';
        document.getElementById('sys-lat').textContent    = t.api_latency+' ms';
        document.getElementById('sys-uptime').textContent = t.network_load;
        document.getElementById('sys-defense').textContent= t.defense_level;
        document.getElementById('sys-routes').textContent = t.active_routes;
        document.getElementById('sys-badge').textContent  = t.grid_status === 'ONLINE' ? '● ONLINE' : '⚠ DEGRADED';
        document.getElementById('sys-badge').className    = t.grid_status === 'ONLINE' ? 'badge badge-green' : 'badge badge-red-blink';

        // Revenue chart
        const months  = res.charts.revenue.map(d => d.month);
        const amounts = res.charts.revenue.map(d => d.amount);
        if (chartRev) chartRev.destroy();
        chartRev = new ApexCharts(document.getElementById('chart-revenue'), {
            series:[{name:'Revenue',data:amounts}],
            chart:{type:'area',height:190,toolbar:{show:false},background:'transparent'},
            stroke:{curve:'smooth',colors:['#06b6d4'],width:2},
            fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:.45,opacityTo:.02,colorStops:[{offset:0,color:'#06b6d4',opacity:.4},{offset:100,color:'#06b6d4',opacity:0}]}},
            grid:{borderColor:'#1e293b',strokeDashArray:4},
            xaxis:{categories:months,labels:{style:{colors:'#64748b',fontFamily:'Share Tech Mono'},fontSize:'9px'}},
            yaxis:{labels:{style:{colors:'#64748b',fontFamily:'Share Tech Mono'},formatter:v=>'$'+v.toLocaleString(),fontSize:'9px'}},
            dataLabels:{enabled:false},theme:{mode:'dark'}
        });
        chartRev.render();

        // Category donut
        const cats = {};
        res.charts.categories.forEach(c => { cats[c.category||'Other'] = parseInt(c.count); });
        if (chartCat) chartCat.destroy();
        chartCat = new ApexCharts(document.getElementById('chart-categories'), {
            series: Object.values(cats), labels: Object.keys(cats),
            chart:{type:'donut',height:190,background:'transparent'},
            colors:['#06b6d4','#a855f7','#f59e0b','#10b981','#ec4899','#6366f1','#f97316'],
            dataLabels:{enabled:false},
            legend:{position:'bottom',labels:{colors:'#94a3b8'},fontSize:'9px'},
            stroke:{colors:['#050810']},theme:{mode:'dark'}
        });
        chartCat.render();

        // Activity feed
        const feed = document.getElementById('activity-feed');
        feed.textContent = '';
        (res.recent_logs || []).forEach(l => {
            const op = l.user ? l.user.name : 'SYSTEM';
            const ts = (l.created_at||'').slice(11,19)||'--:--:--';
            feed.textContent += `[${ts}] ${(l.action||'').padEnd(22)} ‣ ${op} ‣ ${l.description||''}\n`;
        });

    } catch(e) { toast('Dashboard load failed: '+e.message, 'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 2. USERS
// ══════════════════════════════════════════════════════════════════════════════
async function loadUsers() {
    try {
        const d = await api(`${API}/users`);
        const users = d.users || d;
        document.getElementById('nb-users').textContent = Array.isArray(users) ? users.length : '?';
        const tb = document.getElementById('users-tbody');
        tb.innerHTML = '';
        (Array.isArray(users) ? users : []).forEach(u => {
            const sb = u.status==='active'?'badge-green':u.status==='suspended'?'badge-red-blink':'badge-slate';
            tb.innerHTML += `<tr>
                <td class="text-slate-200 font-semibold">${u.name}</td>
                <td class="neon-cyan text-[10px]">${u.email}</td>
                <td><span class="badge badge-purple">${u.role?.name||'--'}</span></td>
                <td><span class="badge ${sb}">${u.status?.toUpperCase()}</span></td>
                <td class="text-slate-600">${u.last_active ? new Date(u.last_active).toLocaleDateString() : 'N/A'}</td>
                <td class="text-right"><button onclick="deleteUser(${u.id})" class="btn btn-danger" style="padding:.2rem .5rem;font-size:.6rem">PURGE</button></td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function createUser(e) {
    e.preventDefault();
    try {
        await api(`${API}/users`,{method:'POST',body:{name:document.getElementById('u-name').value,email:document.getElementById('u-email').value,password:document.getElementById('u-pass').value,status:document.getElementById('u-status').value}});
        toast('Operative initialized.','green'); closeModal('user-modal'); loadUsers();
        document.getElementById('u-name').value=''; document.getElementById('u-email').value=''; document.getElementById('u-pass').value='';
    } catch(e) { toast(e.message,'red'); }
}
async function deleteUser(id) {
    if (!confirm('Purge agent node '+id+'?')) return;
    try { await api(`${API}/users/${id}`,{method:'DELETE'}); toast('Agent purged.','red'); loadUsers(); }
    catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 3. CLIENTS
// ══════════════════════════════════════════════════════════════════════════════
async function loadClients() {
    try {
        const d = await api(`${API}/clients`);
        cachedClients = Array.isArray(d) ? d : (d.data || []);
        const tb = document.getElementById('clients-tbody');
        if (tb) {
            tb.innerHTML = '';
            cachedClients.forEach(c => {
                const sb = c.status==='active'?'badge-green':c.status==='watch_list'?'badge-red-blink':'badge-slate';
                tb.innerHTML += `<tr>
                    <td class="text-slate-200 font-semibold">${c.company||'--'}</td>
                    <td>${c.name}</td>
                    <td class="neon-cyan text-[10px]">${c.email}</td>
                    <td class="neon-gold">$${parseFloat(c.balance||0).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                    <td><span class="badge ${sb}">${c.status?.toUpperCase()}</span></td>
                    <td class="text-right"><button onclick="deleteClient(${c.id})" class="btn btn-danger" style="padding:.2rem .5rem;font-size:.6rem">REMOVE</button></td>
                </tr>`;
            });
        }
        // Populate all client dropdowns
        ['tx-client','ord-client','tk-client'].forEach(selId => {
            const sel = document.getElementById(selId);
            if (!sel) return;
            const cur = sel.value;
            sel.innerHTML = '<option value="">-- select corp --</option>';
            cachedClients.forEach(c => { sel.innerHTML += `<option value="${c.id}">${c.company||c.name}</option>`; });
            if (cur) sel.value = cur;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function createClient(e) {
    e.preventDefault();
    try {
        await api(`${API}/clients`,{method:'POST',body:{name:document.getElementById('cl-name').value,company:document.getElementById('cl-company').value,email:document.getElementById('cl-email').value,phone:document.getElementById('cl-phone').value,balance:parseFloat(document.getElementById('cl-balance').value)||0,status:document.getElementById('cl-status').value}});
        toast('Corp client registered.','green'); closeModal('client-modal'); loadClients();
    } catch(e) { toast(e.message,'red'); }
}
async function deleteClient(id) {
    if (!confirm('Remove client '+id+'?')) return;
    try { await api(`${API}/clients/${id}`,{method:'DELETE'}); toast('Client purged.','red'); loadClients(); }
    catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 4. PRODUCTS
// ══════════════════════════════════════════════════════════════════════════════
async function loadProducts() {
    try {
        const d = await api(`${API}/products`);
        cachedProducts = Array.isArray(d) ? d : (d.data || []);
        const tb = document.getElementById('products-tbody');
        if (tb) {
            tb.innerHTML = '';
            cachedProducts.forEach(p => {
                const sb = p.status==='active'?'badge-green':p.status==='out_of_stock'?'badge-red-blink':'badge-slate';
                tb.innerHTML += `<tr>
                    <td class="text-slate-200 font-semibold">${p.name}</td>
                    <td class="neon-cyan font-mono text-[10px]">${p.sku}</td>
                    <td><span class="badge badge-purple">${p.category||'--'}</span></td>
                    <td class="neon-gold">$${parseFloat(p.price||0).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                    <td class="${parseInt(p.stock)===0?'text-red-400':'text-slate-300'}">${p.stock}</td>
                    <td><span class="badge ${sb}">${p.status?.toUpperCase()}</span></td>
                    <td class="text-right"><button onclick="deleteProduct(${p.id})" class="btn btn-danger" style="padding:.2rem .5rem;font-size:.6rem">DECOM</button></td>
                </tr>`;
            });
        }
        // Populate product dropdown
        const ordSel = document.getElementById('ord-product');
        if (ordSel) {
            ordSel.innerHTML = '<option value="">-- select asset --</option>';
            cachedProducts.forEach(p => { ordSel.innerHTML += `<option value="${p.id}">${p.name} ($${parseFloat(p.price).toLocaleString()})</option>`; });
        }
    } catch(e) { toast(e.message,'red'); }
}
async function createProduct(e) {
    e.preventDefault();
    try {
        await api(`${API}/products`,{method:'POST',body:{name:document.getElementById('pr-name').value,type:document.getElementById('pr-type').value,price:parseFloat(document.getElementById('pr-price').value),stock:parseInt(document.getElementById('pr-stock').value)}});
        toast('Asset uploaded.','green'); closeModal('product-modal'); loadProducts();
    } catch(e) { toast(e.message,'red'); }
}
async function deleteProduct(id) {
    if (!confirm('Decommission product '+id+'?')) return;
    try { await api(`${API}/products/${id}`,{method:'DELETE'}); toast('Asset decommissioned.','red'); loadProducts(); }
    catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 5. ORDERS
// ══════════════════════════════════════════════════════════════════════════════
async function loadOrders() {
    try {
        const d = await api(`${API}/orders`);
        const orders = Array.isArray(d) ? d : (d.data || []);
        const tb = document.getElementById('orders-tbody');
        tb.innerHTML = '';
        orders.forEach(o => {
            const ss = o.status==='completed'?'badge-green':o.status==='cancelled'?'badge-red':o.status==='processing'?'badge-cyan':'badge-yellow';
            const ps = o.payment_status==='paid'?'badge-green':o.payment_status==='refunded'?'badge-slate':'badge-yellow';
            tb.innerHTML += `<tr>
                <td class="neon-cyan font-mono text-[10px]">${o.order_number}</td>
                <td>${o.client?.company||o.client?.name||'--'}</td>
                <td>${o.items?.length||0} item(s)</td>
                <td class="neon-gold">$${parseFloat(o.total_amount||0).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td><span class="badge ${ss}">${o.status?.toUpperCase()}</span></td>
                <td><span class="badge ${ps}">${o.payment_status?.toUpperCase()}</span></td>
                <td class="text-slate-600">${new Date(o.created_at).toLocaleDateString()}</td>
                <td class="text-right">
                    <select onchange="updateOrderStatus(${o.id},this.value)" class="cyber-input" style="width:auto;padding:.15rem .4rem;font-size:.6rem">
                        <option value="">SET</option><option>pending</option><option>processing</option><option>completed</option><option>cancelled</option>
                    </select>
                </td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function createOrder(e) {
    e.preventDefault();
    const cId = document.getElementById('ord-client').value;
    const pId = document.getElementById('ord-product').value;
    if (!cId || !pId) { toast('Select client and product.','red'); return; }
    try {
        await api(`${API}/orders`,{method:'POST',body:{client_id:parseInt(cId),items:[{product_id:parseInt(pId),quantity:parseInt(document.getElementById('ord-qty').value)||1}],payment_method:document.getElementById('ord-gateway').value,simulate_payment:document.getElementById('ord-sim').checked}});
        toast('Order transmitted.','green'); closeModal('order-modal'); loadOrders();
    } catch(e) { toast(e.message,'red'); }
}
async function updateOrderStatus(id, status) {
    if (!status) return;
    try { await api(`${API}/orders/${id}/status`,{method:'PUT',body:{status}}); toast('Order status updated.','cyan'); loadOrders(); }
    catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 6. TRANSACTIONS
// ══════════════════════════════════════════════════════════════════════════════
async function loadTransactions() {
    try {
        const d = await api(`${API}/transactions`);
        const txs = d.data?.transactions || d.transactions || (Array.isArray(d)?d:[]);
        let vol = 0;
        txs.forEach(t => { if(t.status==='success') vol += parseFloat(t.amount||0); });
        document.getElementById('tx-total-vol').textContent = '$'+vol.toLocaleString(undefined,{minimumFractionDigits:2});
        const tb = document.getElementById('tx-tbody');
        tb.innerHTML = '';
        txs.forEach(t => {
            const sb = t.status==='success'?'badge-green':t.status==='failed'?'badge-red-blink':'badge-yellow';
            tb.innerHTML += `<tr>
                <td class="neon-cyan font-mono text-[10px]">${t.transaction_ref}</td>
                <td>${t.client?.company||t.client?.name||'Direct'}</td>
                <td><span class="badge badge-purple">${t.provider}</span></td>
                <td class="neon-gold">$${parseFloat(t.amount||0).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td><span class="badge ${sb}">${t.status?.toUpperCase()}</span></td>
                <td class="text-slate-600">${new Date(t.created_at).toLocaleDateString()}</td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function simPayment(e) {
    e.preventDefault();
    const cId = document.getElementById('tx-client').value;
    if (!cId) { toast('Select a corp client.','red'); return; }
    try {
        await api(`${API}/transactions/simulate`,{method:'POST',body:{client_id:parseInt(cId),amount:parseFloat(document.getElementById('tx-amount').value||1),provider:document.getElementById('tx-provider').value,status:document.getElementById('tx-status').value}});
        toast('Gateway signal transmitted.','green');
        document.getElementById('tx-amount').value='';
        loadTransactions();
    } catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 7. INVOICES
// ══════════════════════════════════════════════════════════════════════════════
async function loadInvoices() {
    try {
        const d = await api(`${API}/invoices`);
        const invs = Array.isArray(d) ? d : (d.data || []);
        const tb = document.getElementById('invoices-tbody');
        tb.innerHTML = '';
        invs.forEach(inv => {
            const sb = inv.status==='paid'?'badge-green':inv.status==='void'?'badge-red':inv.status==='sent'?'badge-cyan':'badge-slate';
            tb.innerHTML += `<tr>
                <td class="neon-cyan font-mono text-[10px]">${inv.invoice_number}</td>
                <td class="text-slate-400 font-mono text-[10px]">${inv.order?.order_number||'--'}</td>
                <td>${inv.order?.client?.company||inv.order?.client?.name||'--'}</td>
                <td class="neon-gold">$${parseFloat(inv.order?.total_amount||0).toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td class="text-slate-500">${inv.issue_date||'--'}</td>
                <td class="text-slate-500">${inv.due_date||'--'}</td>
                <td><span class="badge ${sb}">${inv.status?.toUpperCase()}</span></td>
                <td class="text-right">
                    <select onchange="markInvoice(${inv.id},this.value)" class="cyber-input" style="width:auto;padding:.15rem .4rem;font-size:.6rem">
                        <option value="">SET</option><option>draft</option><option>sent</option><option>paid</option><option>void</option>
                    </select>
                </td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function markInvoice(id, status) {
    if (!status) return;
    try { await api(`${API}/invoices/${id}/status`,{method:'PUT',body:{status}}); toast('Invoice status synchronized.','cyan'); loadInvoices(); }
    catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 8. ANALYTICS
// ══════════════════════════════════════════════════════════════════════════════
async function loadAnalytics() {
    try {
        const [stats, txsRaw] = await Promise.all([api(`${API}/dashboard/stats`), api(`${API}/transactions`)]);
        document.getElementById('ana-revenue').textContent = '$'+parseFloat(stats.metrics.revenue).toLocaleString(undefined,{minimumFractionDigits:2});
        document.getElementById('ana-orders').textContent  = stats.metrics.orders;
        document.getElementById('ana-logs24').textContent  = (stats.recent_logs||[]).length;

        const months  = stats.charts.revenue.map(d=>d.month);
        const amounts = stats.charts.revenue.map(d=>d.amount);
        if (chartAnaRev) chartAnaRev.destroy();
        chartAnaRev = new ApexCharts(document.getElementById('chart-ana-rev'), {
            series:[{name:'Revenue',data:amounts}],
            chart:{type:'bar',height:230,toolbar:{show:false},background:'transparent'},
            colors:['#06b6d4'],grid:{borderColor:'#1e293b',strokeDashArray:4},
            xaxis:{categories:months,labels:{style:{colors:'#64748b',fontFamily:'Share Tech Mono'},fontSize:'9px'}},
            yaxis:{labels:{style:{colors:'#64748b',fontFamily:'Share Tech Mono'},formatter:v=>'$'+v.toLocaleString(),fontSize:'9px'}},
            dataLabels:{enabled:false},theme:{mode:'dark'}
        });
        chartAnaRev.render();

        const prov = txsRaw.data?.provider_chart || txsRaw.provider_chart || [];
        if (prov && prov.length) {
            if (chartProv) chartProv.destroy();
            chartProv = new ApexCharts(document.getElementById('chart-providers'), {
                series: prov.map(p=>parseFloat(p.total||0)), labels: prov.map(p=>p.provider),
                chart:{type:'donut',height:230,background:'transparent'},
                colors:['#06b6d4','#a855f7','#f59e0b','#10b981','#ec4899'],
                dataLabels:{enabled:false},legend:{position:'bottom',labels:{colors:'#94a3b8'},fontSize:'9px'},
                stroke:{colors:['#050810']},theme:{mode:'dark'}
            });
            chartProv.render();
        }
    } catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 9. API KEYS
// ══════════════════════════════════════════════════════════════════════════════
async function loadApiKeys() {
    try {
        const d = await api(`${API}/api-keys`);
        const keys = Array.isArray(d) ? d : (d.data || []);
        const tb = document.getElementById('apikeys-tbody');
        tb.innerHTML = '';
        keys.forEach(k => {
            const sb = k.status==='active'?'badge-green':'badge-red';
            const lh = k.last_used_at ? new Date(k.last_used_at).toLocaleString() : 'Never';
            tb.innerHTML += `<tr>
                <td class="text-slate-200 font-semibold">${k.name}</td>
                <td class="neon-cyan font-mono text-[10px]">${k.key}</td>
                <td><span class="badge ${sb}">${k.status?.toUpperCase()}</span></td>
                <td>${lh}</td>
                <td class="text-right">${k.status==='active'?`<button onclick="revokeKey(${k.id})" class="btn btn-danger" style="padding:.2rem .5rem;font-size:.6rem">REVOKE</button>`:'<span class="badge badge-slate">REVOKED</span>'}</td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function generateKey() {
    const name = prompt('Enter API node name:');
    if (!name) return;
    try { await api(`${API}/api-keys`,{method:'POST',body:{name}}); toast('Access node generated.','green'); loadApiKeys(); }
    catch(e) { toast(e.message,'red'); }
}
async function revokeKey(id) {
    if (!confirm('Revoke access node?')) return;
    try { await api(`${API}/api-keys/${id}/revoke`,{method:'PUT'}); toast('Access node revoked.','red'); loadApiKeys(); }
    catch(e) { toast(e.message,'red'); }
}
async function simulateLatency() {
    const endpoints = [`${API}/dashboard/stats`,`${API}/users`,`${API}/products`,`${API}/clients`];
    const bars = document.getElementById('latency-bars');
    bars.innerHTML = '';
    document.getElementById('latency-display').classList.remove('hidden');
    for (const ep of endpoints) {
        const t0 = Date.now();
        try { await api(ep); } catch(e){}
        const ms = Date.now()-t0;
        const pct = Math.min(ms/500*100,100);
        const col = ms<100?'#10b981':ms<250?'#f59e0b':'#ef4444';
        bars.innerHTML += `<div class="flex items-center gap-2 font-mono text-[9px]">
            <span class="text-slate-500 w-44 truncate">${ep.replace('/api','')}</span>
            <div class="flex-1 bg-slate-900 h-1.5 rounded overflow-hidden"><div class="h-full rounded transition-all" style="width:${pct}%;background:${col}"></div></div>
            <span style="color:${col}">${ms}ms</span>
        </div>`;
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// 10. POSTMAN STRICT VALIDATION TERMINAL
// ══════════════════════════════════════════════════════════════════════════════
const API_SCHEMA = {
    "/api/dashboard/stats":          { GET:{body:[]} },
    "/api/users":                    { GET:{body:[]}, POST:{body:["name","email","password"],schema:{name:"string",email:"string",password:"string"}} },
    "/api/users/{id}":               { GET:{body:[]}, PUT:{body:["name","email"],schema:{name:"string",email:"string"}}, DELETE:{body:[]} },
    "/api/clients":                  { GET:{body:[]}, POST:{body:["name","company","email","balance","status"],schema:{name:"string",company:"string",email:"string",balance:"number",status:"string"}} },
    "/api/clients/{id}":             { GET:{body:[]}, PUT:{body:["name","company","email","balance","status"],schema:{name:"string",company:"string",email:"string",balance:"number",status:"string"}}, DELETE:{body:[]} },
    "/api/products":                 { GET:{body:[]}, POST:{body:["name","sku","price","stock","category","status"],schema:{name:"string",sku:"string",price:"number",stock:"number",category:"string",status:"string"}} },
    "/api/products/{id}":            { GET:{body:[]}, PUT:{body:["name","sku","price","stock","category","status"],schema:{name:"string",sku:"string",price:"number",stock:"number",category:"string",status:"string"}}, DELETE:{body:[]} },
    "/api/orders":                   { GET:{body:[]}, POST:{body:["client_id","items","payment_method"],schema:{client_id:"number",items:"array",payment_method:"string"}} },
    "/api/orders/{id}/status":       { PUT:{body:["status"],schema:{status:"string"}} },
    "/api/transactions":             { GET:{body:[]} },
    "/api/transactions/simulate":    { POST:{body:["client_id","amount","provider","status"],schema:{client_id:"number",amount:"number",provider:"string",status:"string"}} },
    "/api/invoices":                 { GET:{body:[]} },
    "/api/invoices/{id}":            { GET:{body:[]} },
    "/api/invoices/{id}/status":     { PUT:{body:["status"],schema:{status:"string"}} },
    "/api/api-keys":                 { GET:{body:[]}, POST:{body:["name"],schema:{name:"string"}} },
    "/api/api-keys/{id}/revoke":     { PUT:{body:[]} },
    "/api/activity-logs":            { GET:{body:[]} },
    "/api/tickets":                  { GET:{body:[]}, POST:{body:["client_id","subject","description","priority"],schema:{client_id:"number",subject:"string",description:"string",priority:"string"}} },
    "/api/tickets/{id}":             { GET:{body:[]}, PUT:{body:["status"],schema:{status:"string"}}, DELETE:{body:[]} },
    "/api/settings":                 { GET:{body:[]}, PUT:{body:["settings"],schema:{settings:"array"}} },
    "/api/postman/export":           { GET:{body:[]} }
};

const API_TEMPLATES = {
    "/api/users":                  { POST: {name:"Kestrel-09",email:"kestrel09@mercenaryking.net",password:"password"} },
    "/api/users/{id}":             { PUT:  {name:"Kestrel Updated",email:"updated@mercenaryking.net"} },
    "/api/clients":                { POST: {name:"Tyrell Liaison",company:"Tyrell Biotech",email:"nexus@tyrell.bio",balance:450000,status:"active"} },
    "/api/clients/{id}":           { PUT:  {name:"Tyrell Liaison",company:"Tyrell Biotech",email:"nexus@tyrell.bio",balance:500000,status:"active"} },
    "/api/products":               { POST: {name:"Quantum Crypt-Key Decryptor",sku:"SW-QCK-99",price:29500,stock:5,category:"Software",status:"active"} },
    "/api/products/{id}":          { PUT:  {name:"Quantum Crypt-Key Decryptor",sku:"SW-QCK-99",price:31000,stock:4,category:"Software",status:"active"} },
    "/api/orders":                 { POST: {client_id:1,items:[{product_id:1,quantity:1}],payment_method:"NeuralPay",simulate_payment:true} },
    "/api/orders/{id}/status":     { PUT:  {status:"completed"} },
    "/api/transactions/simulate":  { POST: {client_id:1,amount:15000,provider:"NeuralPay",status:"success"} },
    "/api/invoices/{id}/status":   { PUT:  {status:"paid"} },
    "/api/api-keys":               { POST: {name:"Satellite Feed Alpha"} },
    "/api/api-keys/{id}/revoke":   { PUT:  {} },
    "/api/tickets":                { POST: {client_id:1,subject:"CRITICAL: Quantum Key Jammed",description:"Decryptors locked out by corp counter-daemon.",priority:"critical"} },
    "/api/tickets/{id}":           { PUT:  {status:"resolved"} },
    "/api/settings":               { PUT:  {settings:[{key:"grid_status",value:"ONLINE"}]} }
};

function resolvePattern(path) {
    const url = path.split('?')[0].trim();
    for (const pattern of Object.keys(API_SCHEMA)) {
        const rx = new RegExp("^" + pattern.replace(/\{[a-z_]+\}/gi,"([^/]+)") + "$");
        if (rx.test(url)) return pattern;
    }
    return null;
}

function onEndpointChange() {
    const sel = document.getElementById('pm-route');
    const opt = sel.options[sel.selectedIndex];
    const method = opt.getAttribute('data-method') || 'GET';
    const pattern = sel.value;

    document.getElementById('pm-method').value = method;

    const hasParam = pattern.includes('{id}');
    const pw = document.getElementById('pm-param-wrap');
    hasParam ? pw.classList.remove('hidden') : pw.classList.add('hidden');

    updateTargetPath();
    resetTemplate();
    toggleBodyWrap();
    validateRequest();
}

function updateTargetPath() {
    const enabled = document.getElementById('pm-custom-enable').checked;
    if (!enabled) {
        const pattern = document.getElementById('pm-route').value;
        const id = document.getElementById('pm-param-id').value.trim() || '1';
        document.getElementById('pm-custom').value = pattern.replace(/{[a-z_]+}/gi, id);
    }
}

function toggleCustomPath() {
    const enabled = document.getElementById('pm-custom-enable').checked;
    const inp = document.getElementById('pm-custom');
    if (enabled) {
        inp.removeAttribute('readonly');
        inp.classList.remove();
        inp.focus();
    } else {
        inp.setAttribute('readonly','true');
        updateTargetPath();
    }
    validateRequest();
}

function onMethodChange() {
    toggleBodyWrap();
    validateRequest();
}

function toggleBodyWrap() {
    const m = document.getElementById('pm-method').value;
    const w = document.getElementById('pm-body-wrap');
    m==='GET'||m==='DELETE' ? w.classList.add('hidden') : w.classList.remove('hidden');
    w.style.display = (m==='GET'||m==='DELETE') ? 'none' : 'flex';
}

function resetTemplate() {
    const pattern = document.getElementById('pm-route').value;
    const method  = document.getElementById('pm-method').value;
    const ta = document.getElementById('pm-body');
    if (API_TEMPLATES[pattern] && API_TEMPLATES[pattern][method]) {
        ta.value = JSON.stringify(API_TEMPLATES[pattern][method], null, 4);
    } else {
        ta.value = '{}';
    }
    validateRequest();
}

function validateRequest() {
    const errors = [];
    const path   = document.getElementById('pm-custom').value.trim();
    const method = document.getElementById('pm-method').value;
    const body   = document.getElementById('pm-body').value.trim();

    if (!path.startsWith('/api')) errors.push("Path must start with '/api'.");

    const pattern = resolvePattern(path);
    if (!pattern) {
        errors.push("Path does not match any registered endpoint.");
    } else {
        const allowed = Object.keys(API_SCHEMA[pattern]||{});
        if (!allowed.includes(method)) {
            errors.push(`Method '${method}' is NOT allowed for '${pattern}'. Allowed: ${allowed.join(', ')}.`);
        } else if (method==='POST'||method==='PUT') {
            const sch = API_SCHEMA[pattern][method];
            if (sch.body && sch.body.length > 0) {
                try {
                    const parsed = JSON.parse(body||'{}');
                    sch.body.forEach(field => {
                        const val = parsed[field];
                        if (val===undefined||val===null||val==='') {
                            errors.push(`Required field '${field}' is missing.`);
                        } else if (sch.schema && sch.schema[field]) {
                            const t = sch.schema[field];
                            if (t==='array' && !Array.isArray(val)) errors.push(`'${field}' must be an array.`);
                            else if (t==='number' && (typeof val!=='number'||isNaN(val))) errors.push(`'${field}' must be a number.`);
                            else if (t==='boolean' && typeof val!=='boolean') errors.push(`'${field}' must be boolean.`);
                            else if (t==='string' && typeof val!=='string') errors.push(`'${field}' must be a string.`);
                        }
                    });
                } catch(err) { errors.push(`JSON syntax error: ${err.message}`); }
            }
        }
    }

    const vBadge = document.getElementById('pm-validation-badge');
    const errBox = document.getElementById('pm-errors');
    const btn    = document.getElementById('pm-exec-btn');

    if (errors.length > 0) {
        vBadge.textContent = 'INVALID'; vBadge.className = 'badge badge-red-blink';
        errBox.innerHTML = errors.map(e=>`<div>✕ ${e}</div>`).join('');
        errBox.classList.remove('hidden');
        btn.disabled = true;
        return false;
    } else {
        vBadge.textContent = '✓ VALIDATED'; vBadge.className = 'badge badge-green';
        errBox.classList.add('hidden');
        btn.disabled = false;
        return true;
    }
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

async function execRequest() {
    if (!validateRequest()) { toast('Fix validation errors before executing.','red'); return; }

    const method = document.getElementById('pm-method').value;
    const path   = document.getElementById('pm-custom').value.trim();
    const bodyTxt= document.getElementById('pm-body').value.trim();
    const badge  = document.getElementById('pm-status-badge');
    const out    = document.getElementById('pm-output');
    const t0     = Date.now();

    badge.textContent='TRANSMITTING'; badge.className='badge badge-yellow';
    out.textContent='Transmitting...';

    const opts = { method, headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':CSRF} };
    if (bodyTxt && (method==='POST'||method==='PUT')) {
        try { opts.body = JSON.stringify(JSON.parse(bodyTxt)); }
        catch(e) { badge.textContent='JSON ERR'; badge.className='badge badge-red-blink'; out.textContent='JSON parse error: '+e.message; return; }
    }

    try {
        const r = await fetch(path, opts);
        const ms = Date.now()-t0;
        document.getElementById('pm-http-code').textContent = r.status;
        document.getElementById('pm-time').textContent = ms+' ms';

        const text = await r.text();
        document.getElementById('pm-size').textContent = text.length+' B';

        if (text.trim().startsWith('<')) {
            out.innerHTML = `<span style="color:#ef4444;font-weight:bold">⚠ INVALID API RESPONSE — HTML DETECTED</span>\n\nThis means a Laravel error page leaked.\nCheck /bootstrap/app.php exception handlers.\n\nRaw snippet:\n${escHtml(text.slice(0,300))}...`;
            badge.textContent='HTML ERR'; badge.className='badge badge-red-blink'; return;
        }

        try {
            const json = JSON.parse(text);
            out.textContent = JSON.stringify(json, null, 2);
            badge.textContent = r.ok ? '✓ OK' : '⚠ ERROR';
            badge.className = r.ok ? 'badge badge-green' : 'badge badge-red';
        } catch(je) {
            out.textContent = 'Non-JSON response:\n\n'+text;
            badge.textContent='PARSE ERR'; badge.className='badge badge-red';
        }
    } catch(e) { out.textContent='Network error: '+e.message; badge.textContent='FAILED'; badge.className='badge badge-red-blink'; }
}

// ══════════════════════════════════════════════════════════════════════════════
// 11. AUDIT LOGS
// ══════════════════════════════════════════════════════════════════════════════
async function loadLogs() {
    try {
        const d = await api(`${API}/activity-logs`);
        const logs = Array.isArray(d) ? d : (d.data||[]);
        const tb = document.getElementById('logs-tbody');
        tb.innerHTML = '';
        logs.forEach(l => {
            const op = l.user ? l.user.name : 'SYSTEM';
            tb.innerHTML += `<tr>
                <td class="text-slate-600">${new Date(l.created_at).toLocaleString()}</td>
                <td class="text-slate-200">${op}</td>
                <td><span class="badge badge-cyan">${l.action}</span></td>
                <td class="text-slate-400">${l.description}</td>
                <td class="neon-cyan">${l.ip_address||'127.0.0.1'}</td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 12. TICKETS
// ══════════════════════════════════════════════════════════════════════════════
async function loadTickets() {
    try {
        const d = await api(`${API}/tickets`);
        const tickets = d.tickets || (Array.isArray(d)?d:[]);
        document.getElementById('nb-tickets').textContent = tickets.filter(t=>t.status==='open'||t.status==='in_progress').length || '';
        const tb = document.getElementById('tickets-tbody');
        tb.innerHTML = '';
        tickets.forEach(t => {
            const pb = t.priority==='critical'?'badge-red-blink':t.priority==='high'?'badge-yellow':t.priority==='medium'?'badge-cyan':'badge-slate';
            const sb = t.status==='resolved'?'badge-green':t.status==='in_progress'?'badge-cyan':t.status==='closed'?'badge-slate':'badge-yellow';
            tb.innerHTML += `<tr>
                <td class="text-slate-400">#${t.id}</td>
                <td class="neon-purple">${t.client?.company||'Direct'}</td>
                <td class="text-slate-200">${t.subject}</td>
                <td><span class="badge ${pb}">${t.priority?.toUpperCase()}</span></td>
                <td><span class="badge ${sb}">${t.status?.toUpperCase()}</span></td>
                <td class="text-right">
                    <select onchange="updateTicketStatus(${t.id},this.value)" class="cyber-input" style="width:auto;padding:.15rem .4rem;font-size:.6rem">
                        <option value="">SET</option><option>open</option><option>in_progress</option><option>resolved</option><option>closed</option>
                    </select>
                </td>
            </tr>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function submitTicket(e) {
    e.preventDefault();
    const cId = document.getElementById('tk-client').value;
    if (!cId) { toast('Select a client.','red'); return; }
    try {
        await api(`${API}/tickets`,{method:'POST',body:{client_id:parseInt(cId),subject:document.getElementById('tk-subject').value,description:document.getElementById('tk-desc').value,priority:document.getElementById('tk-priority').value}});
        toast('Support channel opened.','green'); closeModal('ticket-modal'); loadTickets();
    } catch(e) { toast(e.message,'red'); }
}
async function updateTicketStatus(id, status) {
    if (!status) return;
    try { await api(`${API}/tickets/${id}`,{method:'PUT',body:{status}}); toast('Ticket updated.','cyan'); loadTickets(); }
    catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 13. SETTINGS
// ══════════════════════════════════════════════════════════════════════════════
async function loadSettings() {
    try {
        const d = await api(`${API}/settings`);
        const settings = Array.isArray(d) ? d : (d.data||[]);
        const grid = document.getElementById('settings-grid');
        grid.innerHTML = '';
        settings.forEach(s => {
            grid.innerHTML += `<div class="glass p-3 flex flex-col gap-1">
                <div class="font-cyber text-[9px] neon-gold tracking-widest">${s.key.replace(/_/g,' ').toUpperCase()}</div>
                <div class="font-mono text-[9px] text-slate-500">${s.description||''}</div>
                <div class="font-mono text-[8px] text-slate-700">Group: ${s.group||'--'}</div>
                <input type="text" data-key="${s.key}" value="${s.value||''}" class="cyber-input mt-1">
            </div>`;
        });
    } catch(e) { toast(e.message,'red'); }
}
async function saveSettings(e) {
    e.preventDefault();
    const inputs   = document.querySelectorAll('#settings-grid input[data-key]');
    const settings = [...inputs].map(i=>({key:i.dataset.key,value:i.value}));
    try {
        await api(`${API}/settings`,{method:'PUT',body:{settings}});
        toast('Grid matrix recalibrated.','green');
    } catch(e) { toast(e.message,'red'); }
}

// ══════════════════════════════════════════════════════════════════════════════
// 14. DEVOPS
// ══════════════════════════════════════════════════════════════════════════════
async function loadDevops() {
    try {
        const [statRes, healthRes] = await Promise.all([
            api(`${API}/v1/devops/status`),
            api(`${API}/v1/devops/health`)
        ]);

        if (statRes && statRes.data) {
            const d = statRes.data;
            
            // Pipeline
            const plBadge = d.pipeline.status === 'OPERATIONAL' ? 'badge-green' : 'badge-red';
            document.getElementById('do-pl-status').className = `badge ${plBadge}`;
            document.getElementById('do-pl-status').textContent = d.pipeline.status;
            document.getElementById('do-pl-run').textContent = new Date(d.pipeline.last_run).toLocaleString();
            document.getElementById('do-pl-dur').textContent = d.pipeline.duration_sec + 's';

            // Deployment
            const dpBadge = d.deployment.status === 'DEPLOYED' ? 'badge-cyan' : 'badge-yellow';
            document.getElementById('do-dp-status').className = `badge ${dpBadge}`;
            document.getElementById('do-dp-status').textContent = d.deployment.status;
            document.getElementById('do-dp-env').textContent = d.deployment.environment.toUpperCase();
            document.getElementById('do-dp-time').textContent = new Date(d.deployment.last_deploy).toLocaleString();

            // Git & Postman
            document.getElementById('do-git-hash').textContent = d.git.commit_short;
            document.getElementById('do-git-branch').textContent = d.git.branch;
            
            const pmBadge = d.postman.synced ? 'badge-green' : 'badge-slate';
            document.getElementById('do-pm-sync').className = `badge ${pmBadge}`;
            document.getElementById('do-pm-sync').textContent = d.postman.synced ? 'SYNCED' : 'UNSYNCED';
        }

        if (healthRes && healthRes.data) {
            const h = healthRes.data;
            
            const overallBadge = h.overall === 'HEALTHY' ? 'badge-green' : (h.overall === 'DEGRADED' ? 'badge-yellow' : 'badge-red');
            document.getElementById('do-api-overall').className = `badge ${overallBadge}`;
            document.getElementById('do-api-overall').textContent = h.overall;

            const tb = document.getElementById('do-api-tbody');
            tb.innerHTML = '';
            
            h.endpoints.forEach(ep => {
                const epBadge = ep.status === 'OK' ? 'badge-green' : (ep.status === 'SKIPPED' ? 'badge-slate' : 'badge-red');
                const notes = ep.error || ep.reason || (ep.html ? 'HTML Contamination!' : (!ep.json && ep.status !== 'SKIPPED' ? 'Missing JSON header' : ''));
                
                tb.innerHTML += `<tr>
                    <td class="text-cyan-300 font-bold">${ep.endpoint}</td>
                    <td>${ep.method}</td>
                    <td><span class="badge ${epBadge}">${ep.status}</span></td>
                    <td class="${ep.code >= 400 ? 'text-red-400' : 'text-slate-300'}">${ep.code || '--'}</td>
                    <td class="text-slate-500">${notes}</td>
                </tr>`;
            });
        }
    } catch(e) {
        toast('Failed to load DevOps telemetry: ' + e.message, 'red');
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', async () => {
    lucide.createIcons();

    // Load cross-module data first
    await Promise.all([
        loadClients().catch(()=>{}),
        loadProducts().catch(()=>{})
    ]);

    // Load dashboard
    await loadDashboard();

    // Init Postman workspace
    onEndpointChange();

    // Re-init icons after all dynamic rendering
    setTimeout(() => lucide.createIcons(), 600);
});
</script>
</body>
</html>
