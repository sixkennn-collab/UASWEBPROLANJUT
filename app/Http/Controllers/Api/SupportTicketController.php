<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['client', 'assignedTo'])->orderBy('created_at', 'desc')->get();
        $users = User::all();
        return response()->json([
            'tickets' => $tickets,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $ticket = SupportTicket::create(array_merge($validated, [
            'status' => 'open'
        ]));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'TICKET_CREATE',
            'description' => "Logged ticket #{$ticket->id}: {$ticket->subject}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'Support channel established',
            'ticket' => $ticket->load(['client', 'assignedTo'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'priority' => 'sometimes|required|string|in:low,medium,high,critical',
            'status' => 'sometimes|required|string|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $ticket->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'TICKET_UPDATE',
            'description' => "Updated status/priority of ticket #{$ticket->id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'Support channel calibrated',
            'ticket' => $ticket->load(['client', 'assignedTo'])
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'TICKET_DELETE',
            'description' => "De-allocated ticket channel #{$id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'Support channel closed and archived'
        ]);
    }
}
