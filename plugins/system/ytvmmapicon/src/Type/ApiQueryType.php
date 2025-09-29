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
                        'id' => [ 'type' => 'String' ],
                        'index' => [ 'type' => 'Int' ],
                        'itemId' => [ 'type' => 'String' ],
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
                            'index' => [
                                'label' => 'Index',
                                'description' => 'Index innerhalb von data (0-basiert)',
                                'type' => 'number',
                            ],
                            'itemId' => [
                                'label' => 'Item-ID',
                                'description' => 'Optional: Element per ID auswählen',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    'extensions' => [ 'call' => __CLASS__ . '::resolve' ],
                ],
                'apiBlog' => [
                    'type' => ['listOf' => 'ApiType'],
                    'args' => [
                        'id' => [ 'type' => 'String' ],
                        'limit' => [ 'type' => 'Int' ],
                        'offset' => [ 'type' => 'Int' ],
                    ],
                    'metadata' => [
                        'label' => 'Api Response Blog',
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
                            'limit' => [ 'label' => 'Limit', 'type' => 'number' ],
                            'offset' => [ 'label' => 'Offset', 'type' => 'number' ],
                        ],
                    ],
                    'extensions' => [ 'call' => __CLASS__ . '::resolve' ],
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
		$field = $info->fieldName ?? 'api';
		$id = (string) ($args['id'] ?? '');

		if ($field === 'apiBlog' || $item['template'] == 'com_content.article') {
			$limit = isset($args['limit']) ? (int) $args['limit'] : null;
			$offset = isset($args['offset']) ? (int) $args['offset'] : 0;
			return ApiTypeProvider::getList($id, $limit, $offset);
		}

		$index = isset($args['index']) ? (int) $args['index'] : 0;
		$itemId = isset($args['itemId']) ? (string) $args['itemId'] : null;
		return ApiTypeProvider::getSingle($id, $index, $itemId);
	}

}
