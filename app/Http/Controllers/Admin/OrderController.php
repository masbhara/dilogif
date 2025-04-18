<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::query()
            ->with('items.product')
            ->latest();
            
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by order number
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(10)
            ->withQueryString();
            
        return Inertia::render('admin/orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['status', 'search']),
            'statuses' => [
                Order::STATUS_PENDING => 'Menunggu Konfirmasi',
                Order::STATUS_PROCESSING => 'Sedang Diproses',
                Order::STATUS_REVIEW => 'Review',
                Order::STATUS_COMPLETED => 'Selesai',
                Order::STATUS_CANCELLED => 'Dibatalkan',
            ]
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items.product.category']);
        
        return Inertia::render('admin/orders/Show', [
            'order' => $order,
            'statuses' => [
                Order::STATUS_PENDING => 'Menunggu Konfirmasi',
                Order::STATUS_PROCESSING => 'Sedang Diproses',
                Order::STATUS_REVIEW => 'Review',
                Order::STATUS_COMPLETED => 'Selesai',
                Order::STATUS_CANCELLED => 'Dibatalkan',
            ]
        ]);
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_REVIEW,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ]),
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $order->update([
            'status' => $request->status,
            'notes' => $request->filled('notes') ? $request->notes : $order->notes,
        ]);
        
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Export order to PDF (can be implemented later).
     */
    public function exportPdf(Order $order)
    {
        // Implementation for generating PDF invoice can be added later
        return redirect()->back()->with('error', 'Fitur ini belum tersedia');
    }

    /**
     * Show order statistics.
     */
    public function statistics()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $processingOrders = Order::where('status', Order::STATUS_PROCESSING)->count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $cancelledOrders = Order::where('status', Order::STATUS_CANCELLED)->count();
        
        $recentOrders = Order::with('items.product')
            ->latest()
            ->take(5)
            ->get();
            
        $totalRevenue = Order::where('status', '!=', Order::STATUS_CANCELLED)
            ->sum('total_amount');
            
        return Inertia::render('admin/orders/Statistics', [
            'statistics' => [
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'processingOrders' => $processingOrders,
                'completedOrders' => $completedOrders,
                'cancelledOrders' => $cancelledOrders,
                'totalRevenue' => $totalRevenue,
            ],
            'recentOrders' => $recentOrders,
        ]);
    }
}
