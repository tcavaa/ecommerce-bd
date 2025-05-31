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
                'product_id' => Type::string(),
                'product_name' => Type::nonNull(Type::string()),
                'attributes' => Type::nonNull(Type::string()),
                'quantity' => Type::int(),
                'amount' => Type::nonNull(Type::float()),
                'selected_currency' => Type::nonNull(Type::string()),
            ],
        ]);
    }
}
