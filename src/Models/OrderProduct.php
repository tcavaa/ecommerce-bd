<?php

namespace App\Models;

class OrderProduct extends BaseModel
{
    protected string $table = 'order_products';

    public function add($data): array
    {
        $query = "INSERT INTO {$this->table} 
                (order_id, 
                product_id, 
                product_name, 
                attributes, 
                quantity, 
                amount, 
                selected_currency, 
                created_at, 
                updated_at)
                VALUES 
                (:order_id, 
                :product_id, 
                :product_name, 
                :attributes, 
                :quantity, 
                :amount, 
                :selected_currency, NOW(), NOW())";

        $success = $this->db->query($query, [
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'attributes' => json_encode($data['attributes']),
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'selected_currency' => $data['selected_currency']
        ]);


        if ($success) {
            return [
                'success' => true,
                'id' => $this->db->getLastInsertId()
                ];
        }

        return [
            'success' => false,
            'id' => null
        ];
    }
}
