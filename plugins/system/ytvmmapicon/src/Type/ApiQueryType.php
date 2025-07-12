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


class ApiQueryType
{
    public static function config()
    {
        return [

            'fields' => [

                'api' => [
                    'type' => 'Api',
                    'metadata' => [
                        'label' => 'Api Response Single',
                        'view' => ['com_vmmapicon.api'],
                        'group' => 'Apis',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::api',
                    ],
                ],

            ]

        ];
    }

    public static function api($root, $args)
    {

        $apiRemapped = FieldsHelper::setFieldMappings($root['item']);

        return $apiRemapped;

    }

}
