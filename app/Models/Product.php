<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'active'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventory_movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
