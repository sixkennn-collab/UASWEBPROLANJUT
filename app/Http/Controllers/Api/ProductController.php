<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => Product::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'type' => 'required'
        ]);

        $product = Product::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'PRODUCT_CREATE',
            'description' => "Uploaded new asset spec: {$product->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data successfully stored',
            'data' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'type' => 'sometimes|required'
        ]);

        $product->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'PRODUCT_UPDATE',
            'description' => "Calibrated asset configurations for: {$product->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $product
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'PRODUCT_DELETE',
            'description' => "Decommissioned asset spec: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => null
        ]);
    }
}
