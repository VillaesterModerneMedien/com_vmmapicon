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
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiQueryType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiType;

class SourceListener
{
    public static function initSource($source): void
    {
        $query = [
            ApiQueryType::config(),
        ];

        $types = [
            ['Api', ApiType::config()],
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }

    public static function initCustomizer(Config $config, Metadata $metadata)
    {
        $config->add('customizer.templates', [
            'com_vmmapicon.apiitem' => [
                'label' => 'Api Result Singleview',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                        ],
                    ],
                ],
            ],
        ]);
    }
}
