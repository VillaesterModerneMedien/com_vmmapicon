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


namespace Joomla\Plugin\System\Ytvmmapicon\Listener;

use Joomla\CMS\Table\ContentType;

use Joomla\Plugin\System\Ytvmmapicon\Type\ApisQueryType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApisType;
use YOOtheme\Builder\Source;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Url;

class SourceListener
{
    public function initCustomizer( $config)
    {
        $config->merge([
            'templates' => [
                'com_vmmapicon.api' => [
                    'label' => 'Api Result Singleview',
                    'fieldset' => [
                        'default' => [
                            'fields' => [

                            ],
                        ],
                    ],
                ],

                'com_vmmapicon.apis' => [
                    'label' => 'Api Results Blogview',
                    'fieldset' => [
                        'default' => [
                            'fields' => [

                            ],
                        ],
                    ],

                ],
            ],

        ]);

        //$metadata->set('script:customizer.api', ['src' => Url::to('plugins/system/ytvmmapicon/api.js'), 'defer' => true]);
    }
}
