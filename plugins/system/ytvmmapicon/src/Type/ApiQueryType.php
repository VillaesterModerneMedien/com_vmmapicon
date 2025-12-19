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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\User\User;
use Joomla\Plugin\System\Ytvmmapicon\ApiTypeProvider;
use Villaester\Component\Vmmapicon\Site\Helper\ApiHelper;
use function YOOtheme\app;


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
                        'articleId' => [ 'type' => 'String' ],
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
                            'articleId' => [
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
	            'menutitle' => [
		            'type' => 'MenuItem',
		            'metadata' => [
			            'label' => 'Menü',
			            'group' => 'Menü',
		            ],
		            'extensions' => [
			            'call' => __CLASS__ . '::resolveMenu',
		            ],
	            ],
	            'apiDetails' => [
		            'type' => 'ApiConfigType',
		            'args' => [
			            'id' => [ 'type' => 'String' ],
		            ],
		            'metadata' => [
			            'label' => 'ApiDetails',
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
			            'call' => __CLASS__ . '::resolveApiDetails',
		            ],
	            ],

            ]
        ];
    }
	public static function getApiOptions()
	{
		$model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
		$options [0]['value'] = '';
		$options [0]['text'] = 'Template';
		foreach ($model->getApis() as $api) {
			array_push($options, ['value' => $api['value'], 'text' => $api['text']]);
		}


		return $options;
	}

	public static function resolve($item, $args, $context, $info)
	{
		$app = Factory::getApplication();
		$input = $app->getInput();
		$field = $info->fieldName ?? 'api';
		$id = isset($args['id']) ? (string) $args['id'] : $input->get('id');
		$index = isset($args['index']) ? (int) $args['index'] : $input->get('index');
		$articleId = isset($args['articleId']) ? (string) $args['articleId'] : $input->get('articleId');

		if ($field === 'apiBlog' && $input->get('view') === 'apisingle'){
			$limit = isset($args['limit']) ? (int) $args['limit'] : null;
			$offset = isset($args['offset']) ? (int) $args['offset'] : 0;
			$result = ApiTypeProvider::getList($id, $limit, $offset, true);
			return $result;
		}
		if ($field === 'apiBlog') {
			$limit = isset($args['limit']) ? (int) $args['limit'] : null;
			$offset = isset($args['offset']) ? (int) $args['offset'] : 0;
			$result = ApiTypeProvider::getList($id, $limit, $offset);
			return $result;
		}

		if($index === null) {
			$index = 0;
		}
		$result = ApiTypeProvider::getSingle($id, $index, $articleId);
		return $result[0];
	}
	public static function resolveMenu(){
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		$active = $menu->getActive()->id;

		$item = Factory::getApplication()
			->getMenu('site')
			->getItem($active ?? 0);

		return $item &&
		in_array($item->access, app(User::class)->getAuthorisedViewLevels()) &&
		(!Multilanguage::isEnabled() ||
			in_array($item->language, [Factory::getLanguage()->getTag(), '*']))
			? $item
			: null;

	}

	public static function resolveApiDetails($args, $item, $context, $info){
		$input = Factory::getApplication()->getInput();
		$apiId = false;
		if(array_key_exists('id', $item)) {
			$apiId = $item['id'];
		}
		if(!$apiId && $input->get('id')) {
			$apiId = $input->get('id');
		}
		if(!$apiId) {
			return false;
		}
		$params = ApiHelper::getApiConfig($apiId);

		return $params;
	}

}
