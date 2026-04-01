<?php
// filepath: c:\laragon\www\laravel-api\app\Repositories\OrderRepository.php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function addItem(int $orderId, array $itemData): OrderItem
    {
        return OrderItem::create([
            'order_id' => $orderId,
            ...$itemData
        ]);
    }

    public function updateStatus(int $orderId, string $status): Order
    {
        $order = Order::find($orderId);
        $order->update(['current_state' => $status]);
        return $order;
    }

    public function getByUser(int $userId, array $filters = [])
    {
        return Order::where('id_customer', $userId)
            ->paginate($filters['per_page'] ?? 15);
    }
}