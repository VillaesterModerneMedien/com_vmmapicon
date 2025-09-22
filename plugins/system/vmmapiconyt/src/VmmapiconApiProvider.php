<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 */

namespace Villaester\Plugin\System\Vmmapiconyt;

use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Cache\Controller\CallbackController;

class VmmapiconApiProvider
{
    protected $db;
    protected $cache;

    public function __construct()
    {
        $this->db = Factory::getDbo();

        // Initialize cache
        $config = Factory::getConfig();
        $options = [
            'defaultgroup' => 'plg_system_vmmapicon_yootheme',
            'cachebase' => $config->get('cache_path', JPATH_CACHE),
            'lifetime' => 900, // 15 minutes default
            'storage' => $config->get('cache_handler', 'file')
        ];

        $this->cache = new CallbackController($options);
    }

    /**
     * Get a single API configuration with its data
     *
     * @param int $id
     * @return object|null
     */
    public function get($id)
    {
        $query = $this->db->getQuery(true)
            ->select('*')
            ->from($this->db->quoteName('#__vmmapicon_apis'))
            ->where($this->db->quoteName('id') . ' = ' . (int)$id)
            ->where($this->db->quoteName('published') . ' = 1');

        $this->db->setQuery($query);
        $apiConfig = $this->db->loadObject();

        if (!$apiConfig) {
            return null;
        }

        // Add the live API data to the config object
        $this->enrichWithApiData($apiConfig);

        return $apiConfig;
    }

    /**
     * Get all published API configurations (alias for getAll)
     *
     * @return array
     */
    public function getApis()
    {
        return $this->getAll();
    }

    /**
     * Get all published API configurations
     *
     * @return array
     */
    public function getAll()
    {
        $query = $this->db->getQuery(true)
            ->select('*')
            ->from($this->db->quoteName('#__vmmapicon_apis'))
            ->where($this->db->quoteName('published') . ' = 1')
            ->order($this->db->quoteName('title'));

        $this->db->setQuery($query);
        $apis = $this->db->loadObjectList();

        // Add live API data to each configuration
        foreach ($apis as $api) {
            $this->enrichWithApiData($api);
        }

        return $apis;
    }

    /**
     * Enrich API configuration with live data from the API
     *
     * @param object $apiConfig
     */
    protected function enrichWithApiData(&$apiConfig)
    {
        // Get the mapped data
        $apiData = $this->getApiDataWithMapping($apiConfig);

        // Add each mapped field to the API config object
        foreach ($apiData as $key => $value) {
            $apiConfig->$key = $value;
        }
    }

    /**
     * Get API data with mapping applied
     *
     * @param object $apiConfig
     * @return array
     */
    public function getApiDataWithMapping($apiConfig)
    {
        // Cache key based on API ID and parameters
        $cacheId = 'api_data_' . md5($apiConfig->id . $apiConfig->api_url . $apiConfig->api_params);

        // Try to get from cache
        $cachedData = $this->cache->get(
            function () use ($apiConfig) {
                return $this->fetchApiData($apiConfig);
            },
            [],
            $cacheId
        );

        if ($cachedData === false) {
            return [];
        }

        return $this->applyMapping($cachedData, $apiConfig->api_mapping);
    }

    /**
     * Fetch data from the API
     *
     * @param object $apiConfig
     * @return array|false
     */
    protected function fetchApiData($apiConfig)
    {
        try {
            // Parse API parameters
            $params = json_decode($apiConfig->api_params, true) ?: [];

            // Prepare headers
            $headers = [];
            if (isset($params['head']) && is_array($params['head'])) {
                foreach ($params['head'] as $key => $value) {
                    $headers[$key] = $value;
                }
            }

            // Build URL with query parameters
            $url = $apiConfig->api_url;
            if (isset($params['url']) && is_array($params['url'])) {
                $url .= '?' . http_build_query($params['url']);
            }

            // Get HTTP client
            $http = HttpFactory::getHttp();

            // Prepare request body
            $body = null;
            if (isset($params['body']) && is_array($params['body'])) {
                $body = json_encode($params['body']);
                if (!isset($headers['Content-Type'])) {
                    $headers['Content-Type'] = 'application/json';
                }
            }

            // Make the API call
            $response = null;
            switch (strtoupper($apiConfig->api_method)) {
                case 'POST':
                    $response = $http->post($url, $body, $headers);
                    break;
                case 'PUT':
                    $response = $http->put($url, $body, $headers);
                    break;
                case 'DELETE':
                    $response = $http->delete($url, $headers);
                    break;
                case 'GET':
                default:
                    $response = $http->get($url, $headers);
                    break;
            }

            if ($response->code !== 200) {
                return false;
            }

            // Parse response
            $data = json_decode($response->body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }

            return $data;

        } catch (\Exception $e) {
            // Log error if needed
            Factory::getApplication()->enqueueMessage('API Error: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Apply field mapping to API data
     *
     * @param array $data
     * @param string $mappingJson
     * @return array
     */
    protected function applyMapping($data, $mappingJson)
    {
        if (empty($mappingJson)) {
            return $data;
        }

        $mapping = json_decode($mappingJson, true);
        if (!is_array($mapping)) {
            return $data;
        }

        $mappedData = [];

        foreach ($mapping as $key => $config) {
            // Support both old format {targetField: sourceField} and new format {json_mapping0: {json_path: ..., yootheme_name: ...}}
            if (is_string($config)) {
                // Old format: {targetField: sourceField}
                $sourceField = $config;
                $targetField = $key;
            } elseif (is_array($config) && isset($config['json_path']) && isset($config['yootheme_name'])) {
                // New format: {json_mapping0: {json_path: ..., yootheme_name: ...}}
                $sourceField = $config['json_path'];
                $targetField = $config['yootheme_name'];
                $fieldType  = $config['field_type'] ?? 'String';
            } else {
                continue;
            }

            // Extract value from nested path (supports 'a->b->c' or 'a.b.c')
            $value = $this->extractValueFromPath($data, $sourceField);

            // Clean and validate target field name
            $cleanFieldName = preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $targetField);
            $cleanFieldName = trim($cleanFieldName, '_');
            if ($cleanFieldName === '') {
                continue;
            }
            if (preg_match('/^\d/', $cleanFieldName)) {
                $cleanFieldName = 'f_' . $cleanFieldName;
            }

            // Coerce to expected type for GraphQL
            $value = $this->coerceValue($value, $fieldType ?? 'String');

            // Clean field name for YooTheme (remove special characters)
            $cleanFieldName = preg_replace('/[^a-zA-Z0-9_]/', '_', $targetField);

            $mappedData[$cleanFieldName] = $value;
        }

        return $mappedData;
    }

    /**
     * Extract value from nested array/object using dot notation path
     *
     * @param mixed $data
     * @param string $path
     * @return mixed
     */
    protected function extractValueFromPath($data, $path)
    {
        // Allow both 'a->b->c' and 'a.b.c'
        $keys = preg_split('/->|\./', (string) $path) ?: [];
        $value = $data;

        foreach ($keys as $key) {
            if (is_array($value) && isset($value[$key])) {
                $value = $value[$key];
            } elseif (is_object($value) && isset($value->$key)) {
                $value = $value->$key;
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * Coerce raw value to the desired field type for GraphQL
     */
    protected function coerceValue($value, $fieldType)
    {
        $t = is_string($fieldType) ? strtolower($fieldType) : 'string';
        switch ($t) {
            case 'number':
            case 'float':
            case 'int':
                if (is_numeric($value)) {
                    return (float) $value;
                }
                return null;
            case 'boolean':
                if (is_bool($value)) return $value;
                if (is_string($value)) {
                    $lv = strtolower($value);
                    if ($lv === 'true' || $lv === '1') return true;
                    if ($lv === 'false' || $lv === '0') return false;
                }
                if (is_numeric($value)) return ((int)$value) !== 0;
                return null;
            case 'array':
            case 'object':
                // Expect listOf(String) at GraphQL layer, return list of strings
                if (is_array($value)) {
                    // if list: map items; if assoc: wrap as single json string
                    $isList = array_is_list($value);
                    if ($isList) {
                        return array_map(function($v){
                            return is_scalar($v) ? (string)$v : json_encode($v, JSON_UNESCAPED_SLASHES);
                        }, $value);
                    }
                    return [json_encode($value, JSON_UNESCAPED_SLASHES)];
                }
                if (is_object($value)) {
                    return [json_encode($value, JSON_UNESCAPED_SLASHES)];
                }
                // Fallthrough to string
            case 'string':
            default:
                if (is_scalar($value)) return (string) $value;
                if ($value === null) return null;
                return json_encode($value, JSON_UNESCAPED_SLASHES);
        }
    }
}
