<?php
/**
 *
 *
 * \ \    / /  \/  |  \/  |
 *  \ \  / /| \  / | \  / |
 *   \ \/ / | |\/| | |\/| |
 *    \  /  | |  | | |  | |
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH * * @package Joomla.Component
 * @subpackage  com_vmmapico
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */
namespace Villaester\Component\Vmmapicon\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Object\CMSObject;
use Joomla\Utilities\ArrayHelper;

/**
 * Vmmapicon component helper.
 *
 * @since  1.0.0
 */
class ApiHelper extends ContentHelper
{
    /**
     * Check if API configuration is valid and complete for making API calls
     *
     * @param   CMSObject  $apiConfig  The API Config
     *
     * @return  bool  True if API config is valid, false otherwise
     *
     * @since   1.0.0
     */
    public static function isApiConfigValid($apiConfig)
    {
        // Check if essential fields are present and not empty
        if (empty($apiConfig->{'api_url'}) || empty($apiConfig->{'api_method'})) {
            return false;
        }

        // Check if api_params is set and not empty (for existing APIs)
        if (empty($apiConfig->{'api_params'})) {
            return false;
        }

        // Try to decode the JSON parameters
        $decodedParams = json_decode($apiConfig->{'api_params'}, true);

        // Check if JSON is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        // Check if we have at least some parameters or if it's an empty array (which is valid for simple APIs)
        if (!is_array($decodedParams)) {
            return false;
        }

        return true;
    }


    /**
     * Split and Format the parameters for the API
     * @param $allParams
     *
     * @return array
     *
     * @since version
     */
    protected static function _formatParams($allParams)
    {
        // Return empty array if no params provided
        if (empty($allParams)) {
            return [
                'head' => [],
                'url' => '',
                'body' => []
            ];
        }

        $decodedParams = json_decode($allParams, true);

        // Handle JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedParams)) {
            return [
                'head' => [],
                'url' => '',
                'body' => []
            ];
        }

        $apiParams = ArrayHelper::fromObject($decodedParams);
        $params = [
            'head' => [],
            'url' => [],
            'body' => []
        ];

        foreach ($apiParams as $param) {
            // Skip empty parameters
            if (empty($param['key']) || !isset($param['value'])) {
                continue;
            }

            switch ($param['position']) {
                case 'head':
                    $params['head'][] = $param['key'] . ':' . $param['value'];
                    break;
                case 'url':
                    $params['url'][] = $param['key'] . '=' . $param['value'];
                    break;
                case 'body':
                    $params['body'][$param['key']] = $param['value'];
                    break;
                default:
                    break;
            }
        }

        if (isset($params['url']) && is_array($params['url']) && !empty($params['url'])) {
            $urlString = implode('&', $params['url']);
            $params['url'] = $urlString; // Removed urlencode as it might double-encode
        } else {
            $params['url'] = '';
        }

        return $params;
    }

    /**
     * Method to get the data of the selected API.
     *
     * @param   CMSObject  $apiConfig  The API Config
     *
     * @return  string|false  A result string from API-call or false if config is invalid
     */
    public static function getApiResult($apiConfig)
    {
        // Check if API configuration is valid before making the call
        if (!self::isApiConfigValid($apiConfig)) {
            return false;
        }

        $apiParams = self::_formatParams($apiConfig->{'api_params'});

        // Build the full URL
        $url = $apiConfig->{'api_url'};
        if (!empty($apiParams['url'])) {
            $url .= '?' . $apiParams['url'];
        }

        $curl = curl_init();

        $curlOptions = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30, // Increased timeout for better reliability
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $apiConfig->{'api_method'},
            CURLOPT_SSL_VERIFYPEER => false, // For development - should be true in production
            CURLOPT_SSL_VERIFYHOST => false, // For development - should be 2 in production
        ];

        // Add headers if present
        if (!empty($apiParams['head'])) {
            $curlOptions[CURLOPT_HTTPHEADER] = $apiParams['head'];
        }

        // Add body data for POST/PUT requests
        if (!empty($apiParams['body']) && in_array(strtoupper($apiConfig->{'api_method'}), ['POST', 'PUT', 'PATCH'])) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($apiParams['body']);

            // Add Content-Type header if not already set
            if (empty($apiParams['head']) || !preg_grep('/Content-Type/i', $apiParams['head'])) {
                $curlOptions[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
            }
        }

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        curl_close($curl);

        // Handle cURL errors
        if ($response === false || !empty($error)) {
            Factory::getApplication()->enqueueMessage(
                'API Call Error: ' . $error,
                'error'
            );
            return false;
        }

        // Handle HTTP errors
        if ($httpCode >= 400) {
            Factory::getApplication()->enqueueMessage(
                'API HTTP Error: ' . $httpCode . ' - ' . $response,
                'warning'
            );
            return false;
        }

        return $response;
    }
}
