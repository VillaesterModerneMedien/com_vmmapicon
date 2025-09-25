<?php

namespace Joomla\Plugin\System\Ytvmmapicon\Type;

class ApiImage
{
    public static function config(): array
    {
        return [
            'fields' => [
                'src' => [ 'type' => 'String', 'metadata' => [ 'label' => 'Image URL' ] ],
                'alt' => [ 'type' => 'String', 'metadata' => [ 'label' => 'Alt Text' ] ],
                'caption' => [ 'type' => 'String', 'metadata' => [ 'label' => 'Caption' ] ],
            ],
            'metadata' => [ 'type' => true, 'label' => 'API Image' ],
        ];
    }
}

