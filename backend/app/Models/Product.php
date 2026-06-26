<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'stock_quantity',
        'sku',
        'image',
        'images',
        'category_id',
        'status',
        'featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'images' => 'array',
        'status' => 'boolean',
        'featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()   { return $this->belongsTo(Category::class); }
    public function orderItems()  { return $this->hasMany(OrderItem::class); }
    public function reviews()     { return $this->hasMany(Review::class); }
    public function wishlists()   { return $this->hasMany(Wishlist::class); }

    public function getDiscountPercentAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round(($this->compare_price - $this->price) / $this->compare_price * 100);
        }
        return 0;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
