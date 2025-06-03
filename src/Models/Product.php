<?php 

namespace App\Models;

use App\Models\Products\ProductFactory;

class Product extends BaseModel
{
    protected string $table = 'products';

    public function getAll(?string $category = null): array
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];

        if($category && strtolower($category) !== 'all') {
            $query .= " WHERE category = :category";
            $params['category'] = $category;
        }

        $productsData = $this->db->query($query, $params)->get();
        $processedProducts = [];

        foreach ($productsData as $productData) {
            $productInstance = ProductFactory::make($productData);
            $processedProducts[] = $productInstance->getDetails();
        }
              
        return $processedProducts;
    }

    public function getById(string $id): array
    {
        $productData = parent::getById($id); 
        $productInstance = ProductFactory::make($productData);
        
        return $productInstance->getDetails();
    }
}