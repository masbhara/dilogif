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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['url'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
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
