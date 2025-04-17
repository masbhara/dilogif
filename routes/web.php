<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProductsController;

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

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/gallery/{gallery}', [ProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');
    Route::post('products/gallery/order', [ProductController::class, 'updateGalleryOrder'])->name('products.gallery.order');

    // Categories
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
