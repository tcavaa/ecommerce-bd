<?php

namespace App\Models\Products;

class BaseProduct extends ProductType
{
    public function getDetails(): array
    {
        $details = $this->getBaseData();
        $details['attributes'] = $this->fetchAttributes();
        $details['prices'] = $this->fetchPrices();
        $details['gallery'] = $this->decodeGallery();
        
        return $details;
    }
}