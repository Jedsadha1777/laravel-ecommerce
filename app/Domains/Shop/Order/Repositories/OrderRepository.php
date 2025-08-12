<?php

namespace App\Domains\Shop\Order\Repositories;

use App\Domains\Shop\Order\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function createOrderWithItems(array $orderData, array $orderItems)
    {
        return DB::transaction(function () use ($orderData, $orderItems) {
            // Create order
            $order = $this->model->create($orderData);
            
            // Create order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                $order->items()->create($item);
                
                // Reduce product stock
                DB::table('products')
                    ->where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }
            
            // Return with relationships
            return $order->load('items.product');
        });
    }

    public function cancelOrderWithStockRestore(Order $order)
    {
        return DB::transaction(function () use ($order) {
            // Load items with products
            $order->load('items.product');
            
            // Restore stock
            foreach ($order->items as $item) {
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
            
            // Update order status
            $order->update(['status' => 'cancelled']);
            
            return $order->fresh('items.product');
        });
    }


    public function getUserOrders($userId, $perPage = 15, $status = null)
    {
        $query = $this->model->where('user_id', $userId)
                            ->with(['items.product'])
                            ->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->paginate($perPage);
    }

    public function findByOrderNumber($orderNumber, $userId = null)
    {
        $query = $this->model->where('order_number', $orderNumber)
                            ->with(['items.product']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $order = $this->model->find($id);
        
        if (!$order) {
            return null;
        }
        
        $order->update($data);
        return $order;
    }
}