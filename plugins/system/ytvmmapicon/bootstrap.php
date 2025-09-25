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


use Joomla\Plugin\System\Ytvmmapicon\Listener\SourceListener;
use Joomla\Plugin\System\Ytvmmapicon\Listener\TemplateListener;

include_once __DIR__ . '/src/ApiTypeProvider.php';
include_once __DIR__ . '/src/Type/ApiImage.php';
include_once __DIR__ . '/src/Type/ApiType.php';
include_once __DIR__ . '/src/Type/ApiQueryType.php';


return [
    'events' => [
        'source.init' => [
            SourceListener::class => 'initSource'
        ],
        // Registriert Template-Matching für Joomla-Views
        'view' => [
            TemplateListener::class => 'matchTemplate'
        ],
        // Registriert Template-Typen in der YOOtheme Template-Übersicht
        'builder.templates' => [
            TemplateListener::class => 'registerTemplates'
        ],
    ],
];
