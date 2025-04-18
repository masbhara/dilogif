<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'description',
        'featured_image',
        'custom_url',
        'is_active',
        'product_code',
        'product_features',
        'product_values',
        'demo_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'product_features' => 'array',
        'product_values' => 'array',
    ];

    protected $appends = ['url'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            // Generate slug jika kosong
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            
            // Generate kode produk jika kosong
            if (empty($product->product_code)) {
                $product->product_code = self::generateProductCode();
            }
        });

        static::updating(function ($product) {
            // Jika nama berubah, update slug kecuali custom_url diisi
            if ($product->isDirty('name') && empty($product->custom_url)) {
                $product->slug = Str::slug($product->name);
            }
            
            // Jika custom_url diisi, gunakan itu sebagai slug
            if ($product->isDirty('custom_url') && !empty($product->custom_url)) {
                $product->slug = Str::slug($product->custom_url);
            }
        });
    }

    /**
     * Generate a unique product code with format DLXXX
     */
    public static function generateProductCode()
    {
        // Buat kode random dengan format DLXXX (XXX = kombinasi huruf dan angka)
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 3;
        
        do {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, strlen($characters) - 1)];
            }
            
            $productCode = 'DL' . $randomString;
            
            // Cek keunikan kode produk
            $exists = self::withTrashed()->where('product_code', $productCode)->exists();
            
        } while ($exists); // Ulangi jika kode sudah digunakan
        
        return $productCode;
    }

    /**
     * Get the URL to the product page.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('products.show', $this->slug);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
