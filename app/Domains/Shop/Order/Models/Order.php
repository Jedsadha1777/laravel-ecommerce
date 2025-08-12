<?php

namespace App\Domains\Shop\Order\Models;

use App\Traits\UsesUuid;
use App\Domains\Shop\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use UsesUuid;

    protected $fillable = [
        'uuid',
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}