<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        return response()->json(ApiKey::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $key = 'mk_' . Str::random(8) . '_' . Str::random(16);

        $apiKey = ApiKey::create([
            'name' => $validated['name'],
            'key' => $key,
            'status' => 'active'
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'API_KEY_GENERATE',
            'description' => "Initialized secure access API node: {$apiKey->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'Secure API Node initialized',
            'api_key' => $apiKey
        ], 201);
    }

    public function revoke(Request $request, $id)
    {
        $apiKey = ApiKey::findOrFail($id);
        $apiKey->update(['status' => 'revoked']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'API_KEY_REVOKE',
            'description' => "De-authorized secure access API node: {$apiKey->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'API access node de-authorized',
            'api_key' => $apiKey
        ]);
    }
}
