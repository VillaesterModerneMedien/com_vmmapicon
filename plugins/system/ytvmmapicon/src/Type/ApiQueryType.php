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
use Joomla\Plugin\System\Ytvmmapicon\Helper\FieldsHelper;
use Villaester\Component\Vmmapicon\Site\Helper\RouteHelper;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiType;


class ApiQueryType
{
    public static function config()
    {
        // Use ApiType fields definitions to expose them as GraphQL input arguments
        $fields = ApiType::configOld()['fields'];

        return [
            'fields' => [
                'api' => [
                    'type'       => 'Api',
                    'args'       => $fields,
                    'metadata'   => [
                        'label' => 'Api Response Single',
                        'view'  => ['com_vmmapicon.api'],
                        'group' => 'Apis',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::api',
                    ],
                ],

            ],

        ];
    }

    public static function api($root, $args)
    {
        $app = Factory::getApplication();
        // Initialize the API model and ignore default request state
        $mvcFactory = $app->bootComponent('com_vmmapicon')->getMVCFactory();
        /** @var \Villaester\Component\Vmmapicon\Site\Model\ApiModel $apiModel */
        $apiModel = $mvcFactory->createModel('Api', 'Site', ['ignore_request' => true]);

        // Apply GraphQL input arguments as filters to the API query
        foreach ($args as $key => $value) {
            if ($value !== null) {
                $apiModel->setState('filter.' . $key, $value);
            }
        }

        $item = $apiModel->getItem();

        return FieldsHelper::setFieldMappings($item);
    }

}
