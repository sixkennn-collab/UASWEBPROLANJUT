<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ActivityLog;
use App\Models\SupportTicket;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats()
    {
        $userCount = User::count();
        $clientCount = Client::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $totalRevenue = Transaction::where('status', 'success')->sum('amount');
        
        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        $activeTickets = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();

        // Calculate Revenue Chart Data (last 6 months)
        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sum = Transaction::where('status', 'success')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            
            $revenueChart[] = [
                'month' => $month->format('M Y'),
                'amount' => (float)$sum
            ];
        }

        // Product Type distribution
        $productDistribution = Product::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        // Ticket Priority distribution
        $ticketPriority = SupportTicket::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->get();

        // Mock Server Telemetry
        $cpu = rand(15, 68);
        $ram = rand(45, 82);
        $latency = rand(8, 35);
        $networkLoad = rand(100, 999) . ' MB/s';
        
        $gridStatusSetting = Setting::where('key', 'grid_status')->first();
        $defenseSetting = Setting::where('key', 'cyber_defense_level')->first();
        $routesSetting = Setting::where('key', 'active_net_routes')->first();

        return response()->json([
            'metrics' => [
                'users' => $userCount,
                'clients' => $clientCount,
                'products' => $productCount,
                'orders' => $orderCount,
                'revenue' => (float)$totalRevenue,
                'active_tickets' => $activeTickets
            ],
            'recent_logs' => $recentLogs,
            'charts' => [
                'revenue' => $revenueChart,
                'categories' => $productDistribution,
                'tickets' => $ticketPriority
            ],
            'telemetry' => [
                'cpu_load' => $cpu,
                'ram_usage' => $ram,
                'api_latency' => $latency,
                'network_load' => $networkLoad,
                'grid_status' => $gridStatusSetting ? $gridStatusSetting->value : 'ONLINE',
                'defense_level' => $defenseSetting ? $defenseSetting->value : 'SECURE',
                'active_routes' => $routesSetting ? $routesSetting->value : '142',
            ]
        ]);
    }
}
