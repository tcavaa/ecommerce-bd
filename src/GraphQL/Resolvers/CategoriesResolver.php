<?php

namespace App\GraphQL\Resolvers;

use App\Models\Category;

class CategoriesResolver
{
    public static function index(): array
    {
        $category = new Category();
        $data = $category->getAll();
        return $data;
    }
}