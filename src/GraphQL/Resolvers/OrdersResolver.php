<?php

namespace App\GraphQL\Resolvers;

use App\Models\Order;
use App\Models\OrderProduct;
use GraphQL\Type\Definition\ResolveInfo;
//use GraphQL\Type\Definition\CustomScalarType;

class OrdersResolver
{
    public static function addOrder(array $args): int
    {
        try {
            $orderModel = new Order();
            $orderProductModel = new OrderProduct();

            $orderData = [
                'items' => $args['items'],
                'currency' => $args['currency'],
                'status' => $args['status'],
            ];

            $orderResult = $orderModel->add($orderData);

            if (!$orderResult['success']) {
                return [
                    'success' => false,
                    'message' => 'Failed to create order',
                ];
            }

            $orderId = $orderResult['id'];

            foreach ($args['items'] as $product) {
                $orderProductModel->add([
                    'order_id' => $orderId,
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'attributes' => $product['attributes'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'selected_currency' => $product['selected_currency'],
                ]);
            }

             return $orderId;
             
        } catch (\Throwable $e) {
            error_log("OrdersResolver error: " . $e->getMessage());
            throw $e;
        }
    }
    
}
