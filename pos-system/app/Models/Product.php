<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'price'];

    // Relationship: one product has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
