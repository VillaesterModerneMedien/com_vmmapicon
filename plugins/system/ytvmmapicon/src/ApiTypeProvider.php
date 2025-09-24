<?php

namespace Joomla\Plugin\System\Ytvmmapicon;
use Joomla\CMS\Factory;
use Villaester\Component\Vmmapicon\Administrator\Helper\ApiHelper;


/**
 * Custom Type Providerwfsdf
 *
 * @see https://yootheme.com/support/yootheme-pro/joomla/developers-sources#add-custom-sources
 */
class ApiTypeProvider
{
    public static function get($id)
    {

        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $item = $model->getItem($id);

        if (!$item) {
            return null;
        }

        $apiResponse = ApiHelper::getApiResult($item);
        $apiData = json_decode($apiResponse, true);

		$item->mapping_fields = $model->getMapping($id) ?: [];
        $item->api_data = $apiData;

	    //return (object) ['my_field' => "The data for id: {$id}"];

	    $data = $apiData['data'][0]['attributes']['title'];
	    return (object) ['beitragstitel2' => $data, 'beitragstitel3' => "tschö"];
		//return $item;
    }

}
