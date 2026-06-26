<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Revenue
            'total_revenue'      => Order::where('status', 'completed')->sum('total'),
            'today_revenue'      => Order::where('status', 'completed')->whereDate('created_at', today())->sum('total'),
            'monthly_revenue'    => Order::where('status', 'completed')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total'),

            // Orders
            'total_orders'       => Order::count(),
            'pending_orders'     => Order::where('status', 'pending')->count(),
            'processing_orders'  => Order::where('status', 'processing')->count(),
            'completed_orders'   => Order::where('status', 'completed')->count(),
            'cancelled_orders'   => Order::where('status', 'cancelled')->count(),

            // Products
            'total_products'     => Product::count(),
            'active_products'    => Product::where('status', true)->count(),
            'out_of_stock'       => Product::where('stock_quantity', 0)->count(),
            'low_stock_products' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count(),

            // Customers
            'total_customers'    => User::where('is_admin', false)->count(),
            'new_customers_month'=> User::where('is_admin', false)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),

            // Charts / detail
            'recent_orders'      => Order::with('user')->latest()->take(5)->get(),
            'revenue_by_month'   => $this->getRevenueByMonth(),
            'orders_by_status'   => $this->getOrdersByStatus(),
            'top_products'       => $this->getTopProducts(),
        ];

        return response()->json($stats);
    }

    private function getRevenueByMonth()
    {
        return Order::where('status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->reverse()
            ->values();
    }

    private function getOrdersByStatus()
    {
        return Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->status => $item->count]);
    }

    private function getTopProducts()
    {
        return OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
    }
}
