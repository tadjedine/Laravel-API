<?php
// filepath: c:\laragon\www\laravel-api\app\Services\OrderService.php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Services\PaymentService;
use Exception;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartService $cartService,
        private PaymentService $paymentService
    ) {}

    public function createOrder(User $user, array $shippingData, array $billingData): Order
    {
        $cart = $this->cartService->getOrCreateCart($user);

        if ($cart->items->isEmpty()) {
            throw new Exception('Cart is empty');
        }

        $total = $this->cartService->getCartTotal($cart);

        $order = $this->orderRepository->create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => 'pending',
            'shipping_address' => $shippingData,
            'billing_address' => $billingData,
        ]);

        // Add items from cart to order
        foreach ($cart->items as $item) {
            $this->orderRepository->addItem($order->id, [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        return $order;
    }

    public function processPayment(Order $order, array $paymentDetails)
    {
        $result = $this->paymentService->processPayment($order, $paymentDetails);

        if ($result) {
            $this->orderRepository->updateStatus($order->id, 'processing');
            $this->cartService->clearCart($order->user);
        }

        return $result;
    }

    public function updateOrderStatus(Order $order, string $status)
    {
        return $this->orderRepository->updateStatus($order->id, $status);
    }

    public function getOrdersByUser(User $user, array $filters = [])
    {
        return $this->orderRepository->getByUser($user->id, $filters);
    }
}