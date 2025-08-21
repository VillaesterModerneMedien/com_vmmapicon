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
use Joomla\Plugin\System\Ytvmmapicon\ApiTypeProvider;
use Joomla\Plugin\System\Ytvmmapicon\Helper\FieldsHelper;
use Villaester\Component\Vmmapicon\Site\Helper\RouteHelper;


class ApiQueryType
{
    public static function config()
    {
		$apiOptions = self::getApiOptions();

        return [

            'fields' => [

                'api' => [
                    'type' => 'Api',
                    'args' => [
	                    'id' => [
		                    'type' => 'String',
	                    ],
                    ],
                    'metadata' => [
                        'label' => 'Api Response Single',
                        'view' => ['com_vmmapicon.api'],
                        'group' => 'Apis',

                        'fields' => [
	                        'id' => [
		                        'label' => 'Api-ID',
		                        'description' => 'Api auswählen',
		                        'type' => 'select',
		                        'options' => $apiOptions,
		                        // Hinweis für UI: Nach Änderung neu laden, damit Felder aktualisiert werden (Option B)
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
		// Fallback, wenn keine ID gesetzt ist
		$apiId = isset($args['id']) && $args['id'] !== '' ? $args['id'] : null;
		if ($apiId === null) {
			$options = self::getApiOptions();
			$apiId = $options[0]['value'] ?? null;
			if ($apiId === null) {
				return null;
			}
		}

		return ApiTypeProvider::get($apiId);
	}

}
