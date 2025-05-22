<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItemInput',
            'fields' => [
                'product_id' => Type::string(), // nullable (ON DELETE SET NULL)
                'product_name' => Type::nonNull(Type::string()),
                'attributes' => Type::nonNull(Type::string()), // JSON as string
                'quantity' => Type::int(), // optional, defaults to 1
                'amount' => Type::nonNull(Type::float()),
                'selected_currency' => Type::nonNull(Type::string()),
            ],
        ]);
    }
}
