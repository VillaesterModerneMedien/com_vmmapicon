<?php
/**
* @package      DigiNerds Vmmapicon24 Package
*
* @author       Christian Schuelling <info@diginerds.de>
* @copyright    2024 diginerds.de - All rights reserved.
* @license      GNU General Public License version 3 or later
*/
//
    /**
     * @package      DigiNerds Immoscout24 Komponente
     *
     * @author       Christian Schuelling <info@diginerds.de>
     * @copyright    2024 diginerds.de - All rights reserved.
     * @license      GNU General Public License version 3 or later
     */

    namespace Villaester\Component\Vmmapicon\Site\Helper;

    \defined('_JEXEC') or die;

    use Joomla\CMS\Component\ComponentHelper;
    use Joomla\CMS\Factory;
    use Joomla\CMS\Helper\ContentHelper;
    use Joomla\Utilities\ArrayHelper;

    /**
     * Vmmapicon component helper.
     *
     * @since  1.0.0
     */
    class VmmapiconHelper extends ContentHelper
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
			$apiParams = ArrayHelper::fromObject($allParams);
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
			$urlString= implode('&', $params['url']);
			$params['url'] = urlencode($urlString);
			return $params;
		}
        /**
         * Method to get the data of the selected API.
         *
         * @return  mixed  An array of objects on success, false on failure.
         */
        public static function getApiData()
        {
	        $app   = Factory::getApplication();
	        $input = $app->input;

			//All API settings
            $componentParams = ComponentHelper::getParams('com_vmmapicon');

			//Settings of the selected API
			$selectedApi = $input->get('selectedApi');
			$apiConfig = $componentParams->get('apis')->$selectedApi;
	        $apiUrl= $apiConfig->{'api-url'};
			$apiTitle = $apiConfig->{'api-title'};
	        $apiParams = self::_formatParams($apiConfig->{'api-params'});
			$apiPostMethod = $apiConfig->{'api-method'};

	        $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => $apiUrl . '?' . $apiParams['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => $apiPostMethod,
                CURLOPT_HTTPHEADER     => $apiParams['head'],
	            CURLOPT_POSTFIELDS     => $apiParams['body'],
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            return $response;
        }

    }
