<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;

class ProductsResolver
{
    public static function index(?string $category = null): array
    {   
        $product = new Product();
        $data = $product->getAll($category);
        return $data;
    }

    public static function show(string $productId): array
    {
        $product = new Product();
        return $product->getById($productId);
    }
}