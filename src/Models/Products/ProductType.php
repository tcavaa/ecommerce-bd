<?php

namespace App\Models\Products;

use App\Models\Attribute;
use App\Models\Price;

abstract class ProductType
{
    protected array $productData;

    public function __construct(array $productData)
    {
        $this->productData = $productData;
    }
    public function getBaseData(): array
    {
        return $this->productData;
    }

    abstract public function getDetails(): array;

    protected function fetchAttributes(): array
    {
        return (new Attribute())->getByPrId($this->productData['id']);
    }

    protected function fetchPrices(): array
    {
        return (new Price())->getByPrId($this->productData['id']);
    }

    protected function decodeGallery(): ?array
    {
        return isset($this->productData['gallery']) ? json_decode($this->productData['gallery'], true) : [];
    }
}