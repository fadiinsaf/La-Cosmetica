<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $sales = DB::table('orders')
            ->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as total_sales')
            ->whereIn('status', ['preparing', 'delivered'])
            ->first();

        $popularProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->get();

        $categories = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('COUNT(products.id) as total_products')
            )
            ->groupBy('categories.name')
            ->get();

        return response()->json([
            'sales' => $sales,
            'popular_products' => $popularProducts,
            'categories' => $categories,
        ], 200);
    }
}
