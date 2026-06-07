<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStateRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    /**
     * List all orders for the authenticated customer.
     * GET /v1/orders
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getCustomerOrders(
            (int) $request->user()->id_customer
        );

        return response()->json([
            'data' => $orders->map(fn ($order) => [
                'id'            => (int) $order->id_order,
                'reference'     => $order->reference,
                'current_state' => (int) $order->current_state,
                'payment'       => $order->payment,
                'total_paid'    => (float) $order->total_paid,
                'date_add'      => $order->date_add,
            ]),
        ]);
    }

    /**
     * Get a specific order with its details.
     * GET /v1/orders/{orderId}
     */
    public function show(Request $request, int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder(
            $orderId,
            (int) $request->user()->id_customer
        );

        return response()->json([
            'data' => [
                'id'                  => (int) $order->id_order,
                'reference'           => $order->reference,
                'current_state'       => (int) $order->current_state,
                'payment'             => $order->payment,
                'total_products'      => (float) $order->total_products,
                'total_discounts'     => (float) $order->total_discounts,
                'total_shipping'      => (float) $order->total_shipping,
                'total_paid'          => (float) $order->total_paid,
                'total_paid_real'     => (float) $order->total_paid_real,
                'date_add'            => $order->date_add,
                'details'             => $order->details->map(fn ($d) => [
                    'product_id'     => (int) $d->product_id,
                    'product_name'   => $d->product_name,
                    'quantity'       => (int) $d->product_quantity,
                    'unit_price'     => (float) $d->unit_price_tax_incl,
                    'total_price'    => (float) $d->total_price_tax_incl,
                ]),
            ],
        ]);
    }

    /**
     * Update the state of an order.
     * PUT /v1/orders/{orderId}/state
     *
     * Used by:
     *  - Frontend after COD delivery confirmation
     *  - Admin panel for manual state changes
     *  - (Future) Webhook handler for payment gateway callbacks
     */
    public function updateState(UpdateOrderStateRequest $request, int $orderId): JsonResponse
    {
        $order = $this->orderService->updateState(
            $orderId,
            $request->customerId(),
            (int) $request->validated('state'),
        );

        return response()->json([
            'message'       => 'Order state updated.',
            'order_id'      => (int) $order->id_order,
            'current_state' => (int) $order->current_state,
        ]);
    }
}
