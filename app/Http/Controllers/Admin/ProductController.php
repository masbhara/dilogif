<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return Inertia::render('admin/products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('admin/products/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'custom_url' => 'nullable|string|max:255',
        ]);

        $featuredImage = $request->file('featured_image')->store('products', 'public');

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id ?: null,
            'price' => $request->price,
            'description' => $request->description,
            'featured_image' => $featuredImage,
            'custom_url' => $request->custom_url,
        ]);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $image->store('products/gallery', 'public');
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $index
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'gallery');

        return Inertia::render('admin/products/Show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('gallery');
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('admin/products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'custom_url' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id ?: null,
            'price' => $request->price,
            'description' => $request->description,
            'custom_url' => $request->custom_url,
        ];

        // Proses gambar utama jika ada
        if ($request->hasFile('featured_image')) {
            // Hapus gambar lama jika ada
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        // Update produk
        $product->update($data);

        // Proses gambar galeri jika ada
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => $image->store('products/gallery', 'public'),
                    'sort_order' => $product->gallery()->count()
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->featured_image);
        
        foreach ($product->gallery as $gallery) {
            Storage::disk('public')->delete($gallery->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function deleteGalleryImage(ProductGallery $gallery)
    {
        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Gallery image deleted successfully.']);
        }
        
        return redirect()->back()->with('success', 'Gambar galeri berhasil dihapus.');
    }

    public function updateGalleryOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:product_galleries,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            ProductGallery::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Gallery order updated successfully.']);
    }
}
