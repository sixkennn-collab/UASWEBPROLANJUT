<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive,watch_list',
            'balance' => 'required|numeric',
        ]);

        $client = Client::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CLIENT_CREATE',
            'description' => "Registered cyber corporation client: {$client->name} ({$client->company})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['message' => 'Client profile initialized', 'client' => $client], 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'status' => 'sometimes|required|string|in:active,inactive,watch_list',
            'balance' => 'sometimes|required|numeric',
        ]);

        $client->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CLIENT_UPDATE',
            'description' => "Updated credentials for corporation: {$client->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['message' => 'Client profile updated', 'client' => $client]);
    }

    public function destroy(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $name = $client->name;
        $client->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CLIENT_DELETE',
            'description' => "Purged client registration from mainframe: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['message' => 'Client record purged']);
    }
}
