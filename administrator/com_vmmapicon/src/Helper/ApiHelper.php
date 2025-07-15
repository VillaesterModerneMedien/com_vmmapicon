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
		    $apiParams = ArrayHelper::fromObject(json_decode($allParams, true));
			$params = [];

		    foreach  ($apiParams as $param)
		    {
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
			if(isset($params['url']) && is_array($params['url']))
			{
		        $urlString= implode('&', $params['url']);
				$params['url'] = urlencode($urlString);
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
	    public static function getApiResult($apiConfig)
	    {
		    $apiParams = self::_formatParams($apiConfig->{'api-params'});
		    $curl = curl_init();

		    curl_setopt_array($curl, array(
			    CURLOPT_URL            => $apiConfig->{'api-url'} . '?' . $apiParams['url'],
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING       => '',
			    CURLOPT_MAXREDIRS      => 10,
			    CURLOPT_TIMEOUT        => 0,
			    CURLOPT_FOLLOWLOCATION => true,
			    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST  => $apiConfig->{'api-method'},
			    CURLOPT_HTTPHEADER     => $apiParams['head'],
			    CURLOPT_POSTFIELDS     => $apiParams['body'],
		    ));

		    $response = curl_exec($curl);

		    curl_close($curl);

		    return $response;
	    }
    }
