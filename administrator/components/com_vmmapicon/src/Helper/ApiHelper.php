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
	     * Split and Format the parameters for the API
	     * @param $allParams
	     *
	     * @return array
	     *
	     * @since version
	     */
	    protected static function _formatParams($allParams){
			if ($allParams === null){
				return [];
			}
		    $apiParams = ArrayHelper::fromObject(json_decode($allParams, true));
			$params = [];
		    $params['head'] = [];
	        $params['body'] = [];
	        $params['url']  = [];

		    foreach  ($apiParams as $param)
		    {
			    switch ($param['position']) {
				    case 'head':
					    // Header in Form "Key: Value"
					    $params['head'][] = trim($param['key']) . ': ' . $param['value'];
					    break;
				    case 'url':
					    // Query-Parameter als Array sammeln, Encoding macht http_build_query
					    $params['url'][$param['key']] = $param['value'];
					    break;
				    case 'body':
					    $params['body'][$param['key']] = $param['value'];
					    break;
				    default:
					    break;
			    }
		    }
			// Query-String korrekt bauen
			if(!empty($params['url']) && is_array($params['url']))
			{
			    $params['url'] = http_build_query($params['url']);
			}
			else {
				$params['url'] = '';
			}

		    return $params;
	    }
	    /**
	     * Method to get the data of the selected API.
	     *
	     * @param  CMSObject  $apiConfig The API Config
	     *
	     * @return  string  A result string from API-call
	     */
	    public static function getApiResult($apiConfig, $isSingle = false, $articleId = null)
	    {
		    $apiParams = self::_formatParams($apiConfig->{'api_params'});

			if(!empty($apiConfig->{'api_url'})){
			$url = $apiConfig->{'api_url'};
			if(!empty($apiParams['url']) && !$isSingle)
			{
				$url .= '?' . $apiParams['url'];
			} else{
				$url .= '/' . $articleId;
			}
				$curl = curl_init();

				$method = $apiConfig->{'api_method'} ? strtoupper($apiConfig->{'api_method'}) : 'GET';

				$opts = [
					CURLOPT_URL            => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING       => '',
					CURLOPT_MAXREDIRS      => 10,
					CURLOPT_TIMEOUT        => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST  => $method,
					CURLOPT_HTTPHEADER     => $apiParams['head'] ?: [],
				];

				// Body nur setzen, wenn Methode nicht GET ist und Body-Daten vorhanden sind
				if ($method !== 'GET' && !empty($apiParams['body'])) {
					$opts[CURLOPT_POSTFIELDS] = $apiParams['body'];
				}

				curl_setopt_array($curl, $opts);

				$response = curl_exec($curl);

				curl_close($curl);
			}
			else{
				$response = <<<'JSON'
				{
				  "data": [
				    {
				      "type": "articles",
				      "id": "581",
				      "attributes": {
				        "id": 581,
				        "asset_id": 767,
				        "title": "New drilling rig HBR201 for underground",
				        "alias": "new-drilling-rig-hbr201",
				        "state": 1,
				        "access": 1,
				        "created": "2025-02-26 10:33:58",
				        "created_by": 545,
				        "created_by_alias": "",
				        "modified": "2025-05-21 08:25:56",
				        "featured": 0,
				        "language": "en-GB",
				        "hits": 36,
				        "publish_up": "2025-02-26 07:39:11",
				        "publish_down": null,
				        "note": "",
				        "images": {
				          "image_intro": "",
				          "image_intro_alt": "",
				          "float_intro": "",
				          "image_intro_caption": "",
				          "image_fulltext": "",
				          "image_fulltext_alt": "",
				          "float_fulltext": "",
				          "image_fulltext_caption": ""
				        },
				        "metakey": "",
				        "metadesc": "",
				        "metadata": {
				          "robots": "",
				          "author": "",
				          "rights": ""
				        },
				        "version": 6,
				        "featured_up": null,
				        "featured_down": null,
				        "typeAlias": "com_content.article",
				        "text": "<p><strong>Undergound exploration drilling with \"Hütte Bohrtechnik\".</strong></p>\r\n<p>THYSSEN SCHACHTBAU has acquired a new HBR201 drilling rig...</p>",
				        "testfeld": "testinhalt",
				        "bilder": "{\"row0\":{\"field2\":{\"imagefile\":\"images/2021/03/10/tsi-logo3.jpg#joomlaImage://local-images/2021/03/10/tsi-logo3.jpg?width=822&height=822\",\"alt_text\":\"\"}}}",
				        "article-field": "",
				        "tags": []
				      }
				    }
				  ]
				}
				JSON;
			}

		    return $response;
	    }
    }
