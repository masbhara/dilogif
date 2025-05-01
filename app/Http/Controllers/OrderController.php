<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Mail\UserCredentials;
use Illuminate\Validation\Rules\Password;

class OrderController extends Controller
{
    /**
     * Display checkout page.
     */
    public function checkout()
    {
        // Debug: Log checkout request
        \Log::info('Checkout page request', [
            'user_id' => auth()->id(),
            'session_id' => Cart::getSessionId()
        ]);

        // Validasi user login
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan checkout');
        }

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

        // Get user data
        $user = auth()->user();
        
        // Debug: Log data yang akan dikirim ke view
        \Log::info('Sending data to checkout view', [
            'user_id' => $user->id,
            'cart_items_count' => $cartItems->count(),
            'subtotal' => $subtotal,
            'total' => $total
        ]);
        
        return Inertia::render('public/orders/Checkout', [
            'cartItems' => $cartItems,
            'summary' => [
                'subtotal' => $subtotal,
                'adminFee' => $adminFee,
                'discount' => $discount,
                'total' => $total,
                'itemCount' => $cartItems->sum('quantity'),
            ],
            'user' => $user
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        // Debug: Log request data
        \Log::info('Order creation request', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'csrf_token' => $request->header('X-CSRF-TOKEN')
        ]);

        // Validasi user login
        if (!auth()->check()) {
            return back()->with('error', 'Silakan login terlebih dahulu untuk melakukan checkout');
        }

        try {
            DB::beginTransaction();
            
            $sessionId = Cart::getSessionId();
            $cartItems = Cart::with('product')
                ->where('session_id', $sessionId)
                ->get();
                
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Keranjang belanja Anda kosong');
            }

            // Calculate order amounts
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            
            $adminFee = 0;
            $discount = 0;
            $total = $subtotal + $adminFee - $discount;

            // Get user data
            $user = auth()->user();
            
            // Validasi data customer dari form
            if (!$request->customer_phone) {
                return back()->with('error', 'Silakan lengkapi nomor telepon Anda di profil sebelum melakukan checkout');
            }

            if (!$request->customer_name) {
                return back()->with('error', 'Silakan lengkapi nama Anda di profil sebelum melakukan checkout');
            }

            if (!$request->customer_email) {
                return back()->with('error', 'Silakan lengkapi email Anda di profil sebelum melakukan checkout');
            }

            // Generate order number
            $orderNumber = Order::generateOrderNumber();
            
            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'subtotal' => $subtotal,
                'admin_fee' => $adminFee,
                'discount' => $discount,
                'total_amount' => $total,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity
                ]);
            }
            
            // Clear cart
            Cart::where('session_id', $sessionId)->delete();
            
            DB::commit();
            
            // Debug: Log success
            \Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $user->id
            ]);

            // Return Inertia response
            return redirect()->route('orders.thankyou', ['order' => $order->id])
                ->with('success', 'Pesanan berhasil dibuat')
                ->with('order', [
                    'id' => $order->id,
                    'order_number' => $order->order_number
                ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'session_id' => $sessionId ?? null,
                'cart_items' => $cartItems ?? []
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display thank you page after successful order.
     */
    public function thankYou($orderId)
    {
        $order = Order::with(['items.product', 'payment.paymentMethod'])->findOrFail($orderId);
        
        // Ambil semua metode pembayaran yang aktif
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        return Inertia::render('public/cart/ThankYou', [
            'order' => $order,
            'paymentMethods' => $paymentMethods
        ]);
    }

    /**
     * Display order tracking page.
     */
    public function trackOrder($order)
    {
        $orderModel = Order::with(['items.product', 'payment.paymentMethod'])
            ->where('order_number', $order)
            ->first();
            
        // Ambil semua metode pembayaran yang aktif
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
            
        if ($orderModel) {
            return Inertia::render('public/orders/Track', [
                'order' => $orderModel,
                'found' => true,
                'paymentMethods' => $paymentMethods
            ]);
        }
        
        return Inertia::render('public/orders/Track', [
            'found' => false,
            'message' => 'Pesanan tidak ditemukan. Silakan periksa kembali nomor pesanan Anda.'
        ]);
    }

    /**
     * Display a listing of the user's orders.
     */
    public function userOrders(Request $request)
    {
        $user = auth()->user();
        
        $orders = Order::with(['items.product', 'payment.paymentMethod'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return Inertia::render('user/orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        // Make sure user can only view their own orders
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }
        
        $order->load(['items.product', 'payment.paymentMethod']);
        
        return Inertia::render('user/orders/Show', [
            'order' => $order
        ]);
    }
}
