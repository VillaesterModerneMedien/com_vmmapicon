<?php
/**
 * YooTheme Pro Integration Bootstrap
 *
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 */

namespace Villaester\Plugin\System\Vmmapiconyt;

use YOOtheme\Builder;
use YOOtheme\Path;
use Villaester\Plugin\System\Vmmapiconyt\Listener\SourceListener;
use Villaester\Plugin\System\Vmmapiconyt\Listener\TemplateListener;
use Villaester\Plugin\System\Vmmapiconyt\Controller\SourceController;
use Villaester\Plugin\System\Vmmapiconyt\VmmapiconApiProvider;

include_once __DIR__ . '/src/Listener/SourceListener.php';
include_once __DIR__ . '/src/Listener/TemplateListener.php';
include_once __DIR__ . '/src/Controller/SourceController.php';
include_once __DIR__ . '/src/VmmapiconApiProvider.php';
include_once __DIR__ . '/src/Type/VmmapiconApiType.php';
include_once __DIR__ . '/src/Type/VmmapiconApiQueryType.php';

error_log('VMMapiconYT Bootstrap: All classes included successfully');

return [

    'routes' => [

        ['get', '/api/apis', [SourceController::class, 'apis']],

    ],

    'events' => [
        'source.init' => [
            SourceListener::class => ['initSource', -20],
        ],

        'customizer.init' => [
            SourceListener::class => ['initCustomizer', -5],
        ],

        'builder.template' => [
            TemplateListener::class => 'matchTemplate',
        ],
    ]

];
