<?php 

namespace App\Models;
use App\Models\Attributes;
use App\Models\Price;

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

        $products = $this->db->query($query, $params)->get();

        foreach ($products as &$product) {
            $product = $this->getProductDetails($product);
        }
              
        return $products;
    }

    public function getById($id): array
    {
        $product = parent::getById($id);
        return $product ? self::getProductDetails($product) : null;
    }

    private static function getProductDetails(array $product): array
    {
        $product['attributes'] = (new Attribute())->getByPrId($product['id']);
        $product['prices'] = (new Price())->getByPrId($product['id']);
        $product['gallery'] = json_decode($product['gallery'], true);
        return $product;
    }
}