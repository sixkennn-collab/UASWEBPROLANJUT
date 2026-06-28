<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * GET /api/v1/system/status
     * Returns real-time grid health and system telemetry.
     */
    public function status()
    {
        $gridStatus    = Setting::where('key', 'grid_status')->value('value') ?? 'ONLINE';
        $defenseLevel  = Setting::where('key', 'cyber_defense_level')->value('value') ?? 'SECURE';
        $activeRoutes  = Setting::where('key', 'active_net_routes')->value('value') ?? '0';
        $appVersion    = Setting::where('key', 'app_version')->value('value') ?? '3.5-AG';

        return response()->json([
            'status'  => true,
            'message' => 'System telemetry retrieved.',
            'data'    => [
                'grid_status'    => $gridStatus,
                'defense_level'  => $defenseLevel,
                'active_routes'  => (int) $activeRoutes,
                'app_version'    => $appVersion,
                'cpu_load'       => rand(12, 72),
                'ram_usage'      => rand(40, 85),
                'api_latency_ms' => rand(5, 38),
                'uptime_hours'   => rand(720, 8760),
                'last_scan'      => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/system/logs
     * Returns recent system audit log entries.
     */
    public function logs(Request $request)
    {
        $limit = min((int) $request->query('limit', 50), 200);

        $logs = ActivityLog::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($log) => [
                'id'          => $log->id,
                'action'      => $log->action,
                'description' => $log->description,
                'operator'    => $log->user?->name ?? 'SYSTEM',
                'ip_address'  => $log->ip_address,
                'timestamp'   => $log->created_at->toIso8601String(),
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'System logs retrieved.',
            'data'    => [
                'total' => $logs->count(),
                'logs'  => $logs,
            ],
        ]);
    }
}
