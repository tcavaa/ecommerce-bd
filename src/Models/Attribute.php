<?php

namespace App\Models;

class Attribute extends BaseModel 
{
    protected string $table = 'attributes';

    public function getByPrId($productId): array 
    {
        $query = "
            SELECT 
                a.id AS attribute_id,
                a.name,
                a.type,
                ai.id AS item_id,
                ai.value,
                ai.displayValue
            FROM attributes a
            JOIN attribute_items ai ON a.id = ai.attribute_id
            WHERE ai.product_id = :product_id
        ";

        $results = $this->db->query($query, ['product_id' => $productId])->get();

        $attributes = [];

        foreach ($results as $row) {
            $attrId = $row['attribute_id'];

            if (!isset($attributes[$attrId])) {
                $attributes[$attrId] = [
                    'id' => $attrId,
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'items' => [],
                ];
            }

            $attributes[$attrId]['items'][] = [
                'id' => $row['item_id'],
                'value' => $row['value'],
                'displayValue' => $row['displayValue'],
            ];
        }

        return array_values($attributes);

    }
}