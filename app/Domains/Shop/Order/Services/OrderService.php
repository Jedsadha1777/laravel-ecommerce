<?php

namespace App\Domains\Shop\Order\Services;

use App\Domains\Shop\Order\Repositories\OrderRepository;
use App\Domains\Shop\Catalog\Repositories\ProductRepository;
use App\Helpers\OrderHelper;


class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function getUserOrders($userId, $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status');
        
        return $this->orderRepository->getUserOrders($userId, $perPage, $status);
    }

    public function getOrderByNumber($orderNumber, $userId)
    {
        return $this->orderRepository->findByOrderNumber($orderNumber, $userId);
    }

    public function createOrder($user, array $validatedData)
    {
        // Validate products and calculate total
        $totalAmount = 0;
        $orderItems = [];
        
        foreach ($validatedData['items'] as $item) {
            $product = $this->productRepository->findActiveById($item['product_id']);
            
            if (!$product) {
                throw new \Exception("Product not available");
            }
            
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Insufficient stock for {$product->name}");
            }
            
            $subtotal = $product->price * $item['quantity'];
            $totalAmount += $subtotal;
            
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
            ];
        }
        
        // Create order data
        $orderData = [
            'user_id' => $user->id,
            'order_number' => OrderHelper::generateOrderNumber(),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'shipping_address' => $validatedData['shipping_address'],
            'notes' => $validatedData['notes'] ?? null,
        ];
        
        // Create with transaction in repository
        return $this->orderRepository->createOrderWithItems($orderData, $orderItems);
    }

    public function cancelOrder($orderNumber, $userId)
    {
        $order = $this->orderRepository->findByOrderNumber($orderNumber, $userId);
        
        if (!$order) {
            throw new \Exception('Order not found');
        }
        
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw new \Exception('Order cannot be cancelled');
        }
        
        return $this->orderRepository->cancelOrderWithStockRestore($order);
    }

}