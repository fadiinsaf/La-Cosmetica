<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderManagementController extends Controller
{
    public function prepare($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order cannot be prepared'], 400);
        }

        $order->update([
            'status' => 'preparing',
        ]);

        return response()->json([
            'message' => 'Order is now preparing',
            'order' => $order,
        ], 200);
    }

    public function deliver($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'preparing') {
            return response()->json(['message' => 'Order cannot be delivered'], 400);
        }

        $order->update([
            'status' => 'delivered',
        ]);

        return response()->json([
            'message' => 'Order delivered',
            'order' => $order,
        ], 200);
    }
}
