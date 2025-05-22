<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => function () {
                return [
                    'id' => Type::nonNull(Type::string()),
                    'name' => Type::nonNull(Type::string()),
                    'inStock' => Type::nonNull(Type::boolean()),
                    'gallery' => Type::listOf(Type::string()),
                    'description' => Type::string(),
                    'category' => Type::string(),
                    'brand' => Type::string(),
                    'attributes' => Type::listOf(new AttributeType()),
                    'prices' => Type::listOf(new PriceType()),
                ];
            }
        ]);
    }
}
