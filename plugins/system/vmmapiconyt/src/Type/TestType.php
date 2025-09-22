<?php
/**
 * Test Type for debugging
 */

namespace Villaester\Plugin\System\Vmmapiconyt\Type;

class TestType
{
    public static function config()
    {
        return [
            'fields' => [
                'testField' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Test Field'
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve'
                    ]
                ]
            ],
            'metadata' => [
                'type' => true,
                'label' => 'VMMapicon Test Type'
            ]
        ];
    }

    public static function resolve($obj, $args, $context, $info)
    {
        return 'Test Value';
    }
}