<?php

namespace App\Models\Products;

use Exception;

class ProductFactory
{
    public static function make(array $productData): ProductType
    {
        $productType = 'base'; 

        switch ($productType) {
            case 'base':
                return new BaseProduct($productData);
            default:
                throw new Exception("Unknown product type: " . $productType);
        }
    }
}