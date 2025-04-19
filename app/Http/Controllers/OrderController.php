<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display checkout page.
     */
    public function checkout()
    {
        $sessionId = Cart::getSessionId();
        
        $cartItems = Cart::with('product.category')
            ->where('session_id', $sessionId)
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong');
        }
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $adminFee = 0; // Default admin fee, can be configurable
        $discount = 0; // Default discount, can be applied later
        $total = $subtotal + $adminFee - $discount;
        
        return Inertia::render('public/cart/Checkout', [
            'cartItems' => $cartItems,
            'summary' => [
                'subtotal' => $subtotal,
                'adminFee' => $adminFee,
                'discount' => $discount,
                'total' => $total,
                'itemCount' => $cartItems->sum('quantity'),
            ]
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $sessionId = Cart::getSessionId();
        
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong');
        }
        
        // Calculate order amounts
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $adminFee = 0; // Default admin fee, can be configurable
        $discount = 0; // Default discount, can be applied later
        $total = $subtotal + $adminFee - $discount;
        
        try {
            DB::beginTransaction();
            
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(), // Will be null for guests
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'subtotal' => $subtotal,
                'admin_fee' => $adminFee,
                'discount' => $discount,
                'total_amount' => $total,
                'status' => Order::STATUS_PENDING,
                'notes' => $request->notes,
            ]);
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }
            
            // Clear cart
            Cart::where('session_id', $sessionId)->delete();
            
            DB::commit();
            
            return redirect()->route('orders.thankyou', $order->id)
                ->with('success', 'Pesanan berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display thank you page after successful order.
     */
    public function thankYou($orderId)
    {
        $order = Order::with(['items.product', 'payment'])->findOrFail($orderId);
        
        return Inertia::render('public/cart/ThankYou', [
            'order' => $order
        ]);
    }

    /**
     * Display order tracking page.
     */
    public function trackOrder(Request $request)
    {
        if ($request->has('order_number')) {
            $order = Order::with(['items.product'])
                ->where('order_number', $request->order_number)
                ->where('customer_phone', $request->customer_phone)
                ->first();
                
            if ($order) {
                return Inertia::render('public/orders/Track', [
                    'order' => $order,
                    'found' => true
                ]);
            } else {
                return Inertia::render('public/orders/Track', [
                    'found' => false,
                    'message' => 'Pesanan tidak ditemukan. Periksa nomor pesanan dan nomor telepon Anda.'
                ]);
            }
        }
        
        return Inertia::render('public/orders/Track', [
            'found' => null
        ]);
    }

    /**
     * Display a listing of the user's orders.
     */
    public function userOrders(Request $request)
    {
        $query = Order::query()
            ->with('items.product')
            ->where('user_id', auth()->id())
            ->latest();
            
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by order number
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(10)
            ->withQueryString();
            
        return Inertia::render('user/orders/Index', [
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
}
