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
use Joomla\Plugin\System\Ytvmmapicon\ApiTypeProvider;


class ApiQueryType
{
    public static function config()
    {
		$apiOptions = self::getApiOptions();

        return [

            'fields' => [
                'api' => [
                    'type' => 'ApiType',
                    'args' => [
	                    'id' => [
		                    'type' => 'String',
	                    ],
                    ],

                    'metadata' => [
                        'label' => 'Api Response Single',
                        'group' => 'Apis',

                        'fields' => [
	                        'id' => [
		                        'label' => 'Api-ID',
		                        'description' => 'Api auswählen',
		                        'type' => 'select',
		                        'options' => $apiOptions,
		                        'reload' => true,
		                        'refresh' => true,
	                        ],
                        ],
                    ],
                    'extensions' => [
	                    'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ]
        ];
    }
	public static function getApiOptions()
	{
		$model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
		$options = $model->getApis();

		return $options;
	}

	public static function resolve($item, $args, $context, $info)
	{
		$id = (string) $args['id'];
		return ApiTypeProvider::get($id);
	}

}
