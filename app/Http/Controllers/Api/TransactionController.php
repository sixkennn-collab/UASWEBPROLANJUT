<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * GET /api/v1/transactions
     */
    public function index()
    {
        $transactions = Transaction::with(['client:id,name,company', 'order:id,order_number'])
            ->orderBy('created_at', 'desc')
            ->get();

        $chartData = Transaction::selectRaw('provider, sum(amount) as total, count(*) as count')
            ->where('status', 'success')
            ->groupBy('provider')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Transaction log retrieved.',
            'data'    => [
                'transactions'   => $transactions,
                'provider_chart' => $chartData,
            ],
        ]);
    }

    /**
     * POST /api/v1/transactions/simulate
     */
    public function simulate(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount'    => 'required|numeric|min:0.01',
            'provider'  => 'required|string|in:CryptoPay,Stripe,NeuralPay,PayPal,BlockChain-X',
            'status'    => 'required|string|in:success,failed,pending',
        ]);

        $client = Client::findOrFail($validated['client_id']);

        $transaction = Transaction::create([
            'client_id'       => $client->id,
            'order_id'        => null,
            'transaction_ref' => 'TXN-' . strtoupper(Str::random(12)),
            'provider'        => $validated['provider'],
            'amount'          => $validated['amount'],
            'status'          => $validated['status'],
        ]);

        if ($validated['status'] === 'success') {
            $client->increment('balance', $validated['amount']);
        }

        ActivityLog::create([
            'user_id'     => null,
            'action'      => 'PAYMENT_RECEIVED',
            'description' => "Gateway {$validated['provider']} cleared {$validated['amount']} for corp {$client->company}.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Gateway signal transmitted.',
            'data'    => ['transaction' => $transaction->load('client:id,name,company')],
        ], 201);
    }
}
