<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->user();

        $orders = Order::where('user_id', $user->id)->get();

        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $user = Auth::guard('api')->user();

        $order = Order::create([
            'user_id' => $user->id,
            'order_date' => now(),
            'status' => 'pending',
            'total_amount' => 0,
        ]);
        $total = 0;

        foreach ($request->products as $item) {
            $product = Product::where('slug', $item['slug'])->first();

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found: ' . $item['slug'],
                ], 404);
            }

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => 'Not enough stock for product: ' . $product->name,
                ], 400);
            }

            $subtotal = $product->price * $item['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            $product->update([
                'stock' => $product->stock - $item['quantity'],
            ]);

            $total += $subtotal;
        }

        $order->update([
            'total_amount' => $total,
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();

        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cancel($id)
    {
        $user = Auth::guard('api')->user();

        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order cannot be cancelled'], 400);
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return response()->json([
            'message' => 'Order cancelled',
            'order' => $order,
        ], 200);
    }
}
