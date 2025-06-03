<?php

namespace App\Models\Attributes;

class TextAttribute extends AttributeType
{
    public function getFormattedItems(): array
    {
        return [[
            'id' => $this->data['item_id'],
            'value' => $this->data['value'],
            'displayValue' => $this->data['displayValue'],
        ]];
    }
}
