<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 */

namespace Villaester\Plugin\System\Vmmapiconyt\Type;

class VmmapiconApiType
{
    /**
     * Get the GraphQL type configuration
     *
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => function() {
                return self::getDynamicFields();
            },

            'metadata' => [
                'type' => true,
                'label' => 'VMMapicon API'
            ]
        ];
    }

    /**
     * Get dynamic fields based on API configurations
     *
     * @return array
     */
    protected static function getDynamicFields()
    {
        $fields = [
            'id' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'API ID',
                    'group' => 'System'
                ]
            ],
            'title' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'API Title',
                    'group' => 'System'
                ]
            ],
            'api_url' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'API URL',
                    'group' => 'System'
                ]
            ],
            'api_method' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'API Method',
                    'group' => 'System'
                ]
            ]
        ];

        // Get all API configurations to build dynamic fields
        $db = \Joomla\CMS\Factory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName(['id', 'title', 'api_mapping']))
            ->from($db->quoteName('#__vmmapicon_apis'))
            ->where($db->quoteName('published') . ' = 1');

        $db->setQuery($query);
        $apis = $db->loadObjectList();

        // Build dynamic fields from all API mappings
        foreach ($apis as $api) {
            if (!empty($api->api_mapping)) {
                $mapping = json_decode($api->api_mapping, true);
                if (is_array($mapping)) {
                    foreach ($mapping as $key => $config) {
                        // Support both old format {targetField: sourceField} and new format {json_mapping0: {json_path: ..., yootheme_name: ...}}
                        if (is_string($config)) {
                            // Old format: {targetField: sourceField}
                            $targetField = $key;
                        } elseif (is_array($config) && isset($config['yootheme_name'])) {
                            // New format: {json_mapping0: {json_path: ..., yootheme_name: ...}}
                            $targetField = $config['yootheme_name'];
                        } else {
                            continue;
                        }

                        // Clean field name for YOOtheme
                        $cleanFieldName = preg_replace('/[^a-zA-Z0-9_]/', '_', $targetField);

                        if (!isset($fields[$cleanFieldName])) {
                            $fields[$cleanFieldName] = [
                                'type' => 'String', // Default to String, could be made dynamic
                                'metadata' => [
                                    'label' => $targetField,
                                    'group' => 'API Data'
                                ],
                                'extensions' => [
                                    'call' => __CLASS__ . '::resolveField'
                                ]
                            ];
                        }
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Resolve a specific field based on API mapping
     *
     * @param object $apiConfig
     * @param array $args
     * @param mixed $context
     * @param mixed $info
     * @return mixed
     */
    public static function resolveField($apiConfig, $args, $context, $info)
    {
        $fieldName = $info->fieldName;

        // For static fields, return the value directly
        if (isset($apiConfig->$fieldName)) {
            return $apiConfig->$fieldName;
        }

        // For dynamic fields, get the mapped data
        $provider = new \Villaester\Plugin\System\Vmmapiconyt\VmmapiconApiProvider();
        $apiData = $provider->getApiDataWithMapping($apiConfig);

        return isset($apiData[$fieldName]) ? $apiData[$fieldName] : null;
    }

    /**
     * Resolve dynamic fields based on API mapping (deprecated)
     *
     * @param object $apiConfig
     * @param array $args
     * @param mixed $context
     * @param mixed $info
     * @return array
     */
    public static function resolveDynamicFields($apiConfig, $args, $context, $info)
    {
        // Get the API provider
        $provider = new \Villaester\Plugin\System\Vmmapiconyt\VmmapiconApiProvider();

        // Get the API data with mapping applied
        $apiData = $provider->getApiDataWithMapping($apiConfig);

        return $apiData;
    }
}
