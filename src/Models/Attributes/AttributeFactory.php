<?php

namespace App\Models\Attributes;

class AttributeFactory
{
    public static function make(array $data): AttributeType
    {
        return match ($data['type']) {
            'text' => new TextAttribute($data),
            'swatch' => new SwatchAttribute($data),
            // Add other types here
            default => throw new \Exception("Unknown attribute type: " . $data['type']),
        };
    }
}
