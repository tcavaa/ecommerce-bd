<?php

namespace App\Models;
use App\Models\Price;

class Order extends BaseModel
{
    protected string $table = 'orders';

    public function add(array $data): array
    {
        $totalAmount = $this->calculateTotalAmount($data['items']);

        $query = "INSERT INTO 
            {$this->table} 
            (total_amount, currency, 
            status, created_at, updated_at) 
            VALUES 
            (:total_amount, :currency, :status, NOW(), NOW())";

        $success = $this->db->query($query, [
            'total_amount' => $totalAmount,
            'currency' => $data['currency'],
            'status' => $data['status'],
        ]);

        if ($success) {
            $orderId = (int) $this->db->getLastInsertId();
            return [
                'id' => $orderId,
                'success' => true,
            ];
        }
        
        return [
            'success' => false,
            'id' => null
        ];
    }

    private function calculateTotalAmount(array $products): float
    {
        $total = 0.0;
        $priceModel = new Price();

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $currency = $product['selected_currency'];
            $quantity = (int)$product['quantity'];

            $price = $priceModel->getAmountByProductAndCurrency($productId, $currency);

            if ($price === null) {
                throw new \Exception("Price not found for product ID {$productId} in currency {$currency}");
            }

            $total += $price * $quantity;
        }

        return round($total, 2);
    }
}