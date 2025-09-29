<?php
namespace Villaester\Component\Vmmapicon\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

class ApiHelper
{
    protected static function formatParams($allParams)
    {
        if ($allParams === null) {
            return ['head' => [], 'body' => [], 'url' => ''];
        }
        $apiParams = json_decode($allParams, true);
        if (!is_array($apiParams)) {
            return ['head' => [], 'body' => [], 'url' => ''];
        }

        $params = ['head' => [], 'body' => [], 'url' => []];

        foreach ($apiParams as $param) {
            if (!is_array($param)) continue;
            $key = $param['key'] ?? '';
            $val = $param['value'] ?? '';
            $pos = $param['position'] ?? '';
            switch ($pos) {
                case 'head':
                    $params['head'][] = trim($key) . ': ' . $val;
                    break;
                case 'url':
                    $params['url'][$key] = $val;
                    break;
                case 'body':
                    $params['body'][$key] = $val;
                    break;
                default:
                    break;
            }
        }
        $params['url'] = !empty($params['url']) ? http_build_query($params['url']) : '';
        return $params;
    }

    public static function getApiResult(object $apiConfig): string
    {
        $url = $apiConfig->api_url ?? '';
        $method = isset($apiConfig->api_method) ? strtoupper($apiConfig->api_method) : 'GET';
        $params = self::formatParams($apiConfig->api_params ?? null);

        if (!$url) {
            return '';
        }

        if (!empty($params['url'])) {
            $url .= (str_contains($url, '?') ? '&' : '?') . $params['url'];
        }

	    $curl = curl_init();
        $opts = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $params['head'] ?: [],
        ];
        if ($method !== 'GET' && !empty($params['body'])) {
            $opts[CURLOPT_POSTFIELDS] = $params['body'];
        }
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        curl_close($curl);
        return (string) $response;
    }
}

