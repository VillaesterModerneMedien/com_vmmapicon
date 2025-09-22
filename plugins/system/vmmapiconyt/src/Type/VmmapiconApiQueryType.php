<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 */

namespace Villaester\Plugin\System\Vmmapiconyt\Type;

use Villaester\Plugin\System\Vmmapiconyt\VmmapiconApiProvider;

class VmmapiconApiQueryType
{
    /**
     * Get the GraphQL query type configuration
     *
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'vmmapiconPing' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'VMMapicon Ping',
                        'group' => 'VMMapicon'
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::ping',
                        ],
                    ],
                ],
                'vmmapiconApi' => [
                    'type' => 'VmmapiconApi',
                    'args' => [
                        'id' => [
                            'type' => 'String',
                            'metadata' => [
                                'label' => 'API ID'
                            ]
                        ]
                    ],
                    'metadata' => [
                        'label' => 'VMMapicon API',
                        'group' => 'VMMapicon'
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveApi',
                        ],
                    ]
                ],

                'vmmapiconApis' => [
                    'type' => ['listOf' => 'VmmapiconApi'],
                    'metadata' => [
                        'label' => 'VMMapicon APIs (Multiple)',
                        'group' => 'VMMapicon'
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveApis',
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * Simple ping to verify the Source is registered in YOOtheme
     */
    public static function ping($root, $args)
    {
        return 'ok';
    }

    /**
     * Resolve a single API by ID
     *
     * @param mixed $root
     * @param array $args
     * @return object|null
     */
    public static function resolveApi($root, $args)
    {
        $id = null;

        if (isset($args['id']) && $args['id'] !== '') {
            $id = (int) $args['id'];
        } elseif (is_array($root) && isset($root['item']) && is_object($root['item']) && isset($root['item']->id)) {
            $id = (int) $root['item']->id;
        }

        if (!$id) {
            return null;
        }

        $provider = new VmmapiconApiProvider();
        return $provider->get($id);
    }

    /**
     * Resolve all APIs
     *
     * @param mixed $root
     * @param array $args
     * @return array
     */
    public static function resolveApis($root, $args)
    {
        $provider = new VmmapiconApiProvider();
        return $provider->getAll();
    }
}
