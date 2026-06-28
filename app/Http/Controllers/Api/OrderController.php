<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['client', 'items.product'])->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|string|in:CryptoPay,Stripe,NeuralPay,PayPal',
            'simulate_payment' => 'nullable|boolean'
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $client = Client::findOrFail($validated['client_id']);
            
            // Calculate total amount
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Check stock
                if ($product->stock < $itemData['quantity'] && $product->status !== 'out_of_stock') {
                    // Force allow but set stock to 0 or adjust
                    // For the futuristic simulation, we can deduct stock
                }
                
                $price = $product->price;
                $quantity = $itemData['quantity'];
                $totalAmount += $price * $quantity;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'product_name' => $product->name,
                    'product' => $product
                ];
            }

            // Create Order
            $orderNum = 'ORD-' . strtoupper(Str::random(8));
            $simulatePayment = $request->input('simulate_payment', false);

            $order = Order::create([
                'client_id' => $client->id,
                'order_number' => $orderNum,
                'total_amount' => $totalAmount,
                'status' => $simulatePayment ? 'completed' : 'pending',
                'payment_status' => $simulatePayment ? 'paid' : 'unpaid'
            ]);

            // Save items & deduct stock
            foreach ($itemsToCreate as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Deduct stock
                $prod = $item['product'];
                if ($prod->stock >= $item['quantity']) {
                    $prod->decrement('stock', $item['quantity']);
                    if ($prod->stock === 0) {
                        $prod->update(['status' => 'out_of_stock']);
                    }
                }
            }

            // If simulate payment is true, create a successful transaction record
            if ($simulatePayment) {
                $provider = $request->input('payment_method', 'NeuralPay');
                Transaction::create([
                    'order_id' => $order->id,
                    'client_id' => $client->id,
                    'transaction_ref' => 'TXN-' . strtoupper(Str::random(12)),
                    'provider' => $provider,
                    'amount' => $totalAmount,
                    'status' => 'success'
                ]);

                // Adjust client balance if they paid (or deduct from client credit)
                // For demo, we just add transaction
            }

            // Automatically generate Invoice
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => 'INV-' . (1000 + $order->id),
                'issue_date' => Carbon::now()->toDateString(),
                'due_date' => Carbon::now()->addDays(14)->toDateString(),
                'status' => $simulatePayment ? 'paid' : 'sent'
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'ORDER_CREATE',
                'description' => "Processed transaction order {$orderNum} for client {$client->name}. Total: \${$totalAmount}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'message' => 'Secure order transaction processed successfully',
                'order' => $order->load(['client', 'items.product', 'invoice'])
            ], 201);
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'payment_status' => 'sometimes|required|string|in:unpaid,paid,refunded'
        ]);

        $order->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'ORDER_STATUS_UPDATE',
            'description' => "Modified order status {$order->order_number} to " . strtoupper($order->status),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'message' => 'Order status configuration modified',
            'order' => $order->load(['client', 'items.product'])
        ]);
    }
}
