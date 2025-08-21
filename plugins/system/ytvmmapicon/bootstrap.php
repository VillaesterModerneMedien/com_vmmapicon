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


use YOOtheme\Builder;
use YOOtheme\Builder\BuilderConfig;
use Joomla\Plugin\System\Ytvmmapicon\Listener\SourceListener;
use Joomla\Plugin\System\Ytvmmapicon\Listener\TemplateListener;
use Joomla\Plugin\System\Ytvmmapicon\Listener\LoadSourceTypes;
use Joomla\Plugin\System\Ytvmmapicon\Listener\LoadTemplate;
use YOOtheme\Path;


return [

    'events' => [

        'source.init'      => [LoadSourceTypes::class => '@handle'],
        'builder.template' => [TemplateListener::class => '@matchTemplate'],

        BuilderConfig::class => [SourceListener::class => '@initCustomizer'],

    ],

];
