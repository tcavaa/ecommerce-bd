<?php

namespace App\Models\Attributes;

abstract class AttributeType
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    abstract public function getFormattedItems(): array;

    public function getBaseData(): array
    {
        return [
            'id' => $this->data['attribute_id'],
            'name' => $this->data['name'],
            'type' => $this->data['type'],
        ];
    }
}
