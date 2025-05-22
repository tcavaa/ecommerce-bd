<?php

namespace App\Models;

class Price extends BaseModel
{
    protected string $table = 'prices';

    public function getByPrId($productId): array
    {
        $query = "
            SELECT 
                p.amount, 
                c.label, 
                c.symbol
            FROM prices p
            JOIN currencies c ON p.currency = c.label
            WHERE p.product_id = :product_id
        ";

        $results = $this->db->query($query, ['product_id' => $productId])->get();

        $formatted = [];

        foreach ($results as $row) {
            $formatted[] = [
                'amount' => (float)$row['amount'],
                'currency' => [
                    'label' => $row['label'],
                    'symbol' => $row['symbol']
                ]
            ];
        }

        

        return $formatted;
    }

    public function getAmountByProductAndCurrency($productId, string $currency): ?float
    {
        $query = "SELECT amount FROM {$this->table} WHERE product_id = :product_id AND currency = :currency LIMIT 1";
        
        $result = $this->db->query($query, [
            'product_id' => $productId,
            'currency' => $currency
        ])->getOne();

        return $result ? (float) $result['amount'] : null;
    }
}