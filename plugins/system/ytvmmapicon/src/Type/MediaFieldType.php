<?php

    namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use function YOOtheme\trans;

class MediaFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' =>
                [
                    'imagefile' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => trans('Url'),
                        ],
                    ],
                ] +
                (version_compare(JVERSION, '4.0', '>')
                    ? [
                        'alt_text' => [
                            'type' => 'String',
                            'metadata' => [
                                'label' => trans('Alt'),
                            ],
                        ],
                    ]
                    : []),
        ];
    }
}
