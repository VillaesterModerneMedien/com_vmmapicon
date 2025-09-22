<?php
/**
 * @package    plg_system_studiogongdataset
 *
 * @author     Kicktemp GmbH <hello@kicktemp.com>
 * @copyright  Copyright © 2020 Kicktemp GmbH. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://kicktemp.com
 */

class DatasetQueryType
{
    public static function config()
    {
        return [

            'fields' => [

                'dataset' => [
                    'type' => 'DatasetType',
                    'metadata' => [
                        'label' => 'Dataset',
                        'view' => ['com_vmmdatabase.dataset'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::dataset',
                    ],
                ],

                'customDataset' => [

                    'type' => 'DatasetType',

                    'args' => [
                        'id' => [
                            'type' => 'String'
                        ],

                    ],

                    'metadata' => [

                        'label' => 'Dataset',
                        'group' => 'Datasets',

                        'fields' => [
                            'id' => [
                                'label' => 'Select Manually',
                                'description' => "AUswählen du Geier",
                                'type' => 'select-item',
                                'module' => 'dataset',
                                'item_type' => 'ta',
                                'labels' => [
                                    'type' => 'Dataset',
                                ],
                            ],
                        ],

                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],

                ],

            ]

        ];
    }

    public static function dataset($root)
    {
		
        if (isset($root['item'])) {
            return $root['item'];
        }
    }

    public static function resolve($root, array $args)
    {
		
        if (!empty($args['id'])) {
            $datasets = DatasetTypeProvider::get($args['id']);
        } else {
            $datasets = DatasetTypeProvider::query($args);
        }

        return $datasets;
		
    }
}
