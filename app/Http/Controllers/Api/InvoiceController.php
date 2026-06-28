<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['order.client', 'order.items.product'])->orderBy('created_at', 'desc')->get();
        return response()->json($invoices);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['order.client', 'order.items.product'])->findOrFail($id);
        return response()->json($invoice);
    }

    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|string|in:draft,sent,paid,void'
        ]);

        $invoice->update($validated);

        // Also update order status if paid
        if ($validated['status'] === 'paid' && $invoice->order) {
            $invoice->order->update(['payment_status' => 'paid', 'status' => 'completed']);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'INVOICE_STATUS_UPDATE',
            'description' => "Modified invoice {$invoice->invoice_number} state to " . strtoupper($invoice->status),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'Invoice status update synchronized',
            'invoice' => $invoice->load(['order.client'])
        ]);
    }
}
