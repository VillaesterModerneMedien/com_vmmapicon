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
                    'type' => 'ApiType',
                    'args' => [
	                    'id' => [
		                    'type' => 'Int',
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
	                    'call' => [self::class, 'resolve'],
                    ],
                ],
            ]
        ];
    }
    public static function getApiOptions()
    {
        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $options = $model->getApis();

        // Normalize options for YOOtheme select: ensure 'value' and 'text' are strings
        $normalized = [];
        if (is_array($options)) {
            foreach ($options as $opt) {
                $value = null;
                $text  = null;

                if (is_array($opt)) {
                    $value = $opt['value'] ?? ($opt['id'] ?? null);
                    $text  = $opt['text']  ?? ($opt['title'] ?? ($opt['name'] ?? null));
                } elseif (is_object($opt)) {
                    $value = $opt->value ?? ($opt->id ?? null);
                    $text  = $opt->text  ?? ($opt->title ?? ($opt->name ?? null));
                } else {
                    // Scalar fallback: use same as text and value
                    $value = $opt;
                    $text  = $opt;
                }

                if ($value === null || $text === null) {
                    continue;
                }

                $normalized[] = [
                    'value' => (string) $value,
                    'text'  => (string) $text,
                ];
            }
        }

        return $normalized;
    }

	public static function resolve($item, $args, $context, $info)
	{
		return ApiTypeProvider::get($args['id']);
	}

}
