<?php
/**
 * YTVMMapicon Bootstrap
 *
 * @package     Joomla.Plugin
 * @subpackage  System.ytvmmapicon
 */

use YOOtheme\Builder;
use YOOtheme\Path;

// Include all required classes directly
include_once __DIR__ . '/src/Listener/LoadSourceTypes.php';
include_once __DIR__ . '/src/Listener/SourceListener.php';
include_once __DIR__ . '/src/Listener/TemplateListener.php';
include_once __DIR__ . '/src/Type/ApiType.php';
include_once __DIR__ . '/src/Type/ApiQueryType.php';

return [

    'events' => [
        'source.init' => [
            \Joomla\Plugin\System\Ytvmmapicon\Listener\LoadSourceTypes::class => ['handle', -20],
        ],
        'customizer.init' => [
            \Joomla\Plugin\System\Ytvmmapicon\Listener\SourceListener::class => ['initCustomizer', -5],
        ],
        'builder.template' => [
            \Joomla\Plugin\System\Ytvmmapicon\Listener\TemplateListener::class => 'matchTemplate',
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder) {
            if (defined('JDEBUG') && JDEBUG) {
                error_log('YTVMMapicon: Builder extended');
            }
        }
    ]

];
