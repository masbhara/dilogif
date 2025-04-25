<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
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
        // Log all incoming data
        \Log::info('Order data received', ['request_data' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validation failed', ['errors' => $validator->errors()->toArray()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $sessionId = Cart::getSessionId();
        \Log::info('Cart session ID', ['session_id' => $sessionId]);
        
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();
            
        \Log::info('Cart items found', ['count' => $cartItems->count()]);
        
        if ($cartItems->isEmpty()) {
            \Log::warning('Cart is empty');
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
        
        \Log::info('Order amounts calculated', [
            'subtotal' => $subtotal,
            'adminFee' => $adminFee,
            'discount' => $discount,
            'total' => $total
        ]);
        
        try {
            DB::beginTransaction();
            \Log::info('Transaction started');
            
            // Check if user already exists
            $user = User::where('email', $request->customer_email)
                    ->orWhere('whatsapp', $request->customer_phone)
                    ->first();
            
            \Log::info('User existence check', ['exists' => (bool)$user]);
            
            // Create user if doesn't exist
            if (!$user) {
                \Log::info('Creating new user', [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone
                ]);
                
                $user = User::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'whatsapp' => $request->customer_phone,
                    'password' => Hash::make($request->password),
                    'status' => 'active',
                ]);
                
                if (!$user) {
                    throw new \Exception('Gagal membuat user baru');
                }
                
                \Log::info('User created', ['user_id' => $user->id]);
                
                // Check if the role exists before assigning
                $userRole = \Spatie\Permission\Models\Role::where('name', 'user')->first();
                if (!$userRole) {
                    \Log::error('User role does not exist');
                    throw new \Exception('Role user tidak ditemukan');
                }
                
                // Assign user role
                \Log::info('Assigning user role');
                $user->assignRole('user');
                
                // Send login credentials via email
                \Log::info('Sending email with credentials');
                try {
                    Mail::to($user->email)->send(new UserCredentials($user, $request->password));
                    \Log::info('Email sent successfully');
                } catch (\Exception $e) {
                    \Log::error('Failed to send email', ['error' => $e->getMessage()]);
                    // Continue without throwing exception - don't block order creation if email fails
                }
            }
            
            // Create order
            \Log::info('Creating order');
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
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
            
            if (!$order) {
                throw new \Exception('Gagal membuat order');
            }
            
            \Log::info('Order created', ['order_id' => $order->id, 'order_number' => $order->order_number]);
            
            // Create order items
            \Log::info('Creating order items');
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }
            
            \Log::info('Order items created');
            
            // Clear cart
            \Log::info('Clearing cart');
            Cart::where('session_id', $sessionId)->delete();
            
            DB::commit();
            \Log::info('Transaction committed successfully');
            
            return redirect()->route('orders.thankyou', $order->id)
                ->with('success', 'Pesanan berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Order creation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
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

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        // Pastikan user hanya bisa melihat pesanannya sendiri
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini');
        }
        
        $order->load(['items.product', 'payment']);
        
        return Inertia::render('user/orders/Show', [
            'order' => $order
        ]);
    }
}
