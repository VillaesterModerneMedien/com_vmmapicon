<?php
/**  
 * 
 * 
 * \ \    / /  \/  |  \/  | 
 *  \ \  / /| \  / | \  / | 
 *   \ \/ / | |\/| | |\/| | 
 *    \  /  | |  | | |  | | 
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH * * @package Joomla.Component  
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien  
 * @author      Mario Hewera & Kiki Schuelling  
 * @license     GNU General Public License version 2 or later  
 * @author      VMM Development Team  
 * @link        https://villaester.de  
 * @version     1.0.0  
 */



namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Plugin\System\Ytvmmapicon\ApisTypeProvider;
use Joomla\Utilities\ArrayHelper;
use Villaester\Component\Vmmapicon\Site\Helper\RouteHelper;

class ApisQueryType
{
    public static function config()
    {
        return [
            'fields' => [
                'apis' => [
                    'type' => [
                        'listOf' => 'Apis',
                    ],
                    'metadata' => [
                        'label' => 'Api lists',
                        'view' => ['com_vmmapicon.apis'],
                        'group' => 'Api Connections',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::apis',
                    ],
                ],
            ]
        ];
    }

    /**
     * Retrieve a list of remapped real estates.
     *
     * @param   mixed  $root  The root parameter.
     *
     * @return array The array of remapped real estates.
     */
    public static function apis($root)
    {
        $app = Factory::getApplication();

        // Get the FieldsModelField, we need it in a sec
        $mvcFactory = $app->bootComponent('com_vmmapicon')->getMVCFactory();
        /** @var \Villaester\Component\Vmmapicon\Site\Model\ApisModel $apisModel */
        $apisModel = $mvcFactory->createModel('Apis', 'Site', ['ignore_request' => true]);
        $response= $apisModel->getItems();
		$responseList = is_array($response) ? $response : json_decode($response, true);

        $menu = Factory::getApplication()->getMenu()->getActive();
	    $Itemid = $menu->id;

	   /* $resultMapping = [];
        foreach ($responseList as $responseItem => $value)
        {
	        if (is_array($value))
	        {
		        $resultMapping[$responseItem] = ArrayHelper::flatten($value);
	        }
	        else
	        {
		        $resultMapping[$responseItem] = $value;
	        }
        }
		*/
        return $responseList;
    }

}
