<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shoe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'price',
        'deleted_price',
        'image',
        'best_seller',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'deleted_price' => 'decimal:2',
        'best_seller' => 'boolean',
    ];

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return '';
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        return asset('shoes/' . $this->image);
    }
}
