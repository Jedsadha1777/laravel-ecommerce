<?php

namespace App\Domains\Shop\Order\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domains\Shop\Order\Services\OrderService;
use App\Domains\Shop\Order\Requests\PlaceOrderRequest;
use App\Domains\Shop\Order\Resources\OrderResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $orders = $this->orderService->getUserOrders($user->id, $request);
        
        return ApiResponse::paginated($orders, 'Orders retrieved successfully');
    }

    public function store(PlaceOrderRequest $request)
    {
        $user = Auth::guard('api')->user();
        
        try {
            $order = $this->orderService->createOrder($user, $request->validated());
            
            return ApiResponse::success(
                new OrderResource($order),
                'Order placed successfully',
                201
            );
        } catch (\Exception $e) {
           return ApiResponse::error($e->getMessage(), 400);
       }
   }

   public function show($orderNumber)
   {
       $user = Auth::guard('api')->user();
       $order = $this->orderService->getOrderByNumber($orderNumber, $user->id);
       
       if (!$order) {
           return ApiResponse::error('Order not found', 404);
       }
       
       return ApiResponse::success(
           new OrderResource($order),
           'Order retrieved successfully'
       );
   }

   public function cancel($orderNumber)
   {
       $user = Auth::guard('api')->user();
       
       try {
           $order = $this->orderService->cancelOrder($orderNumber, $user->id);
           
           return ApiResponse::success(
               new OrderResource($order),
               'Order cancelled successfully'
           );
       } catch (\Exception $e) {
           return ApiResponse::error($e->getMessage(), 400);
       }
   }
}