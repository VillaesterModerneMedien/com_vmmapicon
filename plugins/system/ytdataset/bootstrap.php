<?php
/**
 * @package    plg_system_studiogongdataset
 *
 * @author     Kicktemp GmbH <hello@kicktemp.com>
 * @copyright  Copyright © 2020 Kicktemp GmbH. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://kicktemp.com
 */

use YOOtheme\Builder;
use YOOtheme\Path;

include_once __DIR__ . '/src/DatasetTypeProvider.php';
include_once __DIR__ . '/src/SourceControllerDataset.php';
include_once __DIR__ . '/src/SourceListener.php';
include_once __DIR__ . '/src/TemplateListener.php';
include_once __DIR__ . '/src/Type/DatasetQueryType.php';
include_once __DIR__ . '/src/Type/DatasetType.php';

return [

    'routes' => [

        ['get', '/dataset/datasets', [SourceControllerDataset::class, 'datasets']],

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
