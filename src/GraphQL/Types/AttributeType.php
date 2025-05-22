<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'name' => Type::nonNull(Type::string()),
                'type' => Type::nonNull(Type::string()),
                'items' => Type::listOf(new AttributeItemType()),
            ],
        ]);
    }
}
