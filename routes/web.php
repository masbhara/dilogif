<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Models\Order;
use App\Http\Controllers\OrderDocumentController;
use App\Http\Controllers\PaymentConfirmationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FixCouponController;

Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Mengambil pesanan terbaru
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Ringkasan pesanan berdasarkan status
        $orderSummary = [
            'pending' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', $user->id)->where('status', 'processing')->count(),
            'review' => Order::where('user_id', $user->id)->where('status', 'review')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];
        
        return Inertia::render('dashboard/Index', [
            'recentOrders' => $recentOrders,
            'orderSummary' => $orderSummary,
            'user' => $user,
        ]);
    })->middleware(['auth', 'verified'])->name('dashboard');
    
    // Dashboard Routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Pages
        Route::resource('pages', PageController::class);
        
        // Services
        Route::resource('services', ServiceController::class);
        
        // Team Members
        Route::resource('team', TeamMemberController::class);
        
        // Contact Messages
        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
    });
    
    // User Orders
    Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');
    
    // PENTING: Route untuk konfirmasi pembayaran user harus didefinisikan sebelum route dengan parameter {order}
    Route::get('/orders/payment', [PaymentConfirmationController::class, 'userIndex'])->name('orders.payment.index');
    
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/documents', [OrderDocumentController::class, 'index'])->name('orders.documents.index');
    Route::get('/orders/{order}/documents/{document}', [OrderDocumentController::class, 'show'])->name('orders.documents.show');
    Route::get('/orders/{order}/documents/{document}/download', [OrderDocumentController::class, 'download'])->name('orders.documents.download');
    Route::post('/orders/{order}/documents/{document}/mark-as-read', [OrderDocumentController::class, 'markAsRead'])->name('orders.documents.mark-as-read');
    Route::get('/my-documents', [OrderDocumentController::class, 'allDocuments'])->name('my-documents');
    
    // Payment Routes
    Route::get('/orders/{order}/payment', [PaymentController::class, 'showOptions'])->name('orders.payment');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'updateMethod'])->name('orders.payment.update');

    // Payment Confirmation Routes
    Route::get('/orders/{order}/payment/confirm', [PaymentConfirmationController::class, 'create'])->name('orders.payment.confirm');
    Route::post('/orders/{order}/payment/confirm', [PaymentConfirmationController::class, 'store'])->name('orders.payment.confirm.store');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    
    // Coupon Routes
    // Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
    // Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');
    
    // Order Routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/thank-you/{order}', [OrderController::class, 'thankYou'])->name('orders.thankyou');
    Route::get('/orders/track/{order}', [OrderController::class, 'trackOrder'])->name('orders.track');
    
    // Public Payment Routes
    Route::get('/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('payment.methods');
});

// Public Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

// Products Routes (Public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes (Public)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Coupon Routes (Public)
Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');

// Public routes with caching
Route::middleware(['http-cache'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});

// Detail produk tanpa cache untuk menghindari masalah JSON
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Products
    Route::resource('products', AdminProductController::class);
    Route::delete('products/gallery/{gallery}', [AdminProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');
    Route::post('products/gallery/order', [AdminProductController::class, 'updateGalleryOrder'])->name('products.gallery.order');

    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Orders
    Route::middleware('permission:view orders')->group(function () {
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
        Route::get('orders/{order}/pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.pdf');
        Route::get('orders-statistics', [AdminOrderController::class, 'statistics'])->name('orders.statistics');
    });
});

// Route sementara untuk perbaikan kupon
Route::get('/fix-coupons', FixCouponController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
