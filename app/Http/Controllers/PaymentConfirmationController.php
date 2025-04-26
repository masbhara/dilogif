<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentConfirmation;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PaymentConfirmationController extends Controller
{
    /**
     * Display payment confirmation form
     */
    public function create(Order $order)
    {
        // Make sure user can only access their own orders
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $order->load(['items.product', 'payment.paymentMethod']);
        
        if (!$order->payment) {
            return redirect()->route('orders.payment', $order)->with('error', 'Pilih metode pembayaran terlebih dahulu');
        }
        
        $lastConfirmation = PaymentConfirmation::where('payment_id', $order->payment->id)
            ->where('status', '!=', PaymentConfirmation::STATUS_REJECTED)
            ->latest()
            ->first();
            
        return Inertia::render('user/orders/ConfirmPayment', [
            'order' => $order,
            'lastConfirmation' => $lastConfirmation,
        ]);
    }
    
    /**
     * Store a new payment confirmation
     */
    public function store(Request $request, Order $order)
    {
        // Make sure user can only access their own orders
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }
        
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1',
            'transfer_date' => 'required|date',
            'proof_image' => 'required|image|max:2048', // max 2MB
            'notes' => 'nullable|string|max:500',
        ]);
        
        $payment = Payment::where('order_id', $order->id)->firstOrFail();
        
        // Handle file upload
        $path = null;
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment-confirmations', 'public');
        }
        
        // Create payment confirmation
        $confirmation = PaymentConfirmation::create([
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'amount' => $request->amount,
            'transfer_date' => $request->transfer_date,
            'proof_image' => $path,
            'notes' => $request->notes,
            'status' => PaymentConfirmation::STATUS_PENDING,
        ]);
        
        return redirect()->route('orders.show', $order)->with('success', 'Konfirmasi pembayaran berhasil dikirim');
    }
    
    /**
     * List all confirmations for admin
     */
    public function index()
    {
        // Make sure only admin can access this
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }
        
        $confirmations = PaymentConfirmation::with(['payment.order', 'user'])
            ->latest()
            ->paginate(10);
            
        return Inertia::render('admin/payment-confirmations/Index', [
            'confirmations' => $confirmations,
        ]);
    }
    
    /**
     * Show payment confirmation details to admin
     */
    public function show(PaymentConfirmation $confirmation)
    {
        // Make sure only admin can access this
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }
        
        $confirmation->load(['payment.order', 'user']);
        
        return Inertia::render('admin/payment-confirmations/Show', [
            'confirmation' => $confirmation,
        ]);
    }
    
    /**
     * Update confirmation status by admin
     */
    public function updateStatus(Request $request, PaymentConfirmation $confirmation)
    {
        // Make sure only admin can access this
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        $confirmation->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);
        
        // If verified, update payment status as well
        if ($request->status === PaymentConfirmation::STATUS_VERIFIED) {
            $payment = $confirmation->payment;
            $payment->status = Payment::STATUS_COMPLETED;
            $payment->save();
            
            // If you want to update order status as well
            // $order = $payment->order;
            // $order->status = Order::STATUS_PROCESSING;
            // $order->save();
        }
        
        return redirect()->back()->with('success', 'Status konfirmasi pembayaran berhasil diperbarui');
    }

    /**
     * Display list of payment confirmations for user
     */
    public function userIndex()
    {
        // Get user's orders that need payment or have pending confirmations
        $pendingOrders = Order::with(['payment.paymentMethod'])
            ->where('user_id', auth()->id())
            ->whereHas('payment', function($query) {
                $query->where('status', 'pending');
            })
            ->latest()
            ->get();
            
        // Get user's payment confirmations history
        $confirmations = PaymentConfirmation::with(['payment.order', 'user'])
            ->whereHas('payment.order', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();
            
        return Inertia::render('user/orders/PaymentIndex', [
            'pendingOrders' => $pendingOrders,
            'confirmations' => $confirmations,
        ]);
    }
} 