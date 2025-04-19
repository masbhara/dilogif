<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', function () {
        return Inertia::render('dashboard/Index');
    })->name('dashboard');
    
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
    
    // User Orders - daftar pesanan pengguna
    Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');
});

// Public Routes
Route::get('/', function () {
    return Inertia::render('public/home/Index');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('public/about/Index');
})->name('about');

Route::get('/services', function () {
    return Inertia::render('public/services/Index');
})->name('services');

Route::get('/contact', function () {
    return Inertia::render('public/contact/Index');
})->name('contact');

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

// Rute Produk Publik
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductsController::class, 'show'])->name('products.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Order Routes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/thankyou/{order}', [OrderController::class, 'thankYou'])->name('orders.thankyou');
Route::get('/track-order', [OrderController::class, 'trackOrder'])->name('orders.track');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/gallery/{gallery}', [ProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');
    Route::post('products/gallery/order', [ProductController::class, 'updateGalleryOrder'])->name('products.gallery.order');

    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::get('orders/{order}/pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.pdf');
    Route::get('orders-statistics', [AdminOrderController::class, 'statistics'])->name('orders.statistics');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
