<?php

namespace App\Domains\Shop\Order\Policies;

use App\Domains\Shop\User\Models\User;
use App\Domains\Shop\Order\Models\Order;

class OrderPolicy
{
    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    public function cancel(User $user, Order $order)
    {
        return $user->id === $order->user_id 
            && in_array($order->status, ['pending', 'processing']);
    }
}