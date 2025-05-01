<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    /**
     * Apply coupon to the cart.
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code',
        ], [
            'code.exists' => 'Kode kupon tidak valid',
        ]);

        $couponCode = $request->code;
        $coupon = Coupon::where('code', $couponCode)->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kupon tidak ditemukan',
            ], 404);
        }

        // Hitung subtotal dari keranjang untuk validasi kupon
        $sessionId = Cart::getSessionId();
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();
            
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Validasi kupon
        if (!$coupon->isValid($subtotal)) {
            $message = 'Kupon tidak dapat digunakan';
            
            if (!$coupon->is_active) {
                $message = 'Kupon tidak aktif';
            } elseif ($coupon->starts_at && now()->isBefore($coupon->starts_at)) {
                $message = 'Kupon belum berlaku';
            } elseif ($coupon->expires_at && now()->isAfter($coupon->expires_at)) {
                $message = 'Kupon sudah kadaluarsa';
            } elseif ($coupon->max_uses > 0 && $coupon->used_count >= $coupon->max_uses) {
                $message = 'Kupon sudah mencapai batas penggunaan';
            } elseif ($subtotal < $coupon->min_purchase) {
                $message = 'Minimal pembelian Rp ' . number_format($coupon->min_purchase, 0, ',', '.') . ' untuk menggunakan kupon ini';
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);
        }

        // Hitung diskon
        $discount = $coupon->calculateDiscount($subtotal);
        
        // Simpan kupon ke session
        Session::put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'description' => $coupon->description,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil diterapkan',
            'coupon' => [
                'code' => $coupon->code,
                'description' => $coupon->description,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount,
                'formattedDiscount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Remove coupon from cart.
     */
    public function remove()
    {
        Session::forget('coupon');
        
        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil dihapus',
        ]);
    }
}
