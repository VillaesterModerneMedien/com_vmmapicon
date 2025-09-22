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

// Autoloader für Plugin-Klassen registrieren
spl_autoload_register(function ($class) {
    $prefix = 'Villaester\\Plugin\\System\\Vmmapiconyt\\';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Debug logging
if (defined('JDEBUG') && JDEBUG) {
    error_log('VMMapiconYT Bootstrap: Loading configuration');
}

return [

    'routes' => [

        ['get', '/api/apis', [SourceController::class, 'apis']],

    ],

    'events' => [
        // Use current YOOtheme event names to avoid double registration
        'source.init' => [
            SourceListener::class => ['initSource', -20],
        ],
        'customizer.init' => [
            SourceListener::class => ['initCustomizer', -5],
        ],
        'builder.template' => [
            TemplateListener::class => 'matchTemplate',
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder) {
            if (defined('JDEBUG') && JDEBUG) {
                error_log('VMMapiconYT: Builder extended');
            }
        }
    ]

];
