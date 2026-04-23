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
    ) {}

    public function createOrder(User $user, array $shippingData, array $billingData): Order
    {

    } 
}