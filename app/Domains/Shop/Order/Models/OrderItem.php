<?php

namespace App\Domains\Shop\Order\Models;

use App\Traits\UsesUuid;
use App\Domains\Shared\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use UsesUuid;

    protected $fillable = [
        'uuid',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}