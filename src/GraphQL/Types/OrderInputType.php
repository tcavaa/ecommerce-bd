<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\OrderItemInputType;

class OrderInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderInput',
            'fields' => [
                'currency' => Type::nonNull(Type::string()),
                'status' => Type::string(), // optional: defaults to 'pending'
                'total_amount' => Type::nonNull(Type::float()),
                'items' => Type::nonNull(Type::listOf(Type::nonNull(new OrderItemInputType()))),
            ],
        ]);
    }
}
