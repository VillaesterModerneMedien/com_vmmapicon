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

//
namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;

use Joomla\CMS\Router\Route;
use Joomla\Component\Categories\Administrator\Model\CategoryModel;
use Joomla\Component\Fields\Administrator\Model\FieldModel;
use \Joomla\Language\Text;
use Joomla\Plugin\System\Ytvmmapicon\Helper\FieldsHelper;
use Joomla\String\StringHelper;
use VmmdatabaseNamespace\Component\Vmmdatabase\Site\Model\DatasetModel;


class ApiType
{

    public function setFields($fieldname, $fieldtype, $label, $tab)
    {
        $array = [
            $fieldname => [
                'type' => $fieldtype,
                'metadata' => [
                    'label' => $label,
                    'group' => $tab
                ],
                'extensions' => [
                    'call' => [
                        'func' => __CLASS__ . '::resolve',
                        'args' => [
                            'fieldname' => $fieldname,
                        ]
                    ]

                ]

            ],
        ];

        return $array;
    }


    public static function configOld2()
    {

        $test = 'not set';

        $db    = Factory::getContainer()->get('DatabaseDriver');

        $app       = Factory::getApplication();

        $session = $app->getSession();
        $datasetId = $session->get('com_vmmapicon.edit.dataset.data.id');

        // Get the FieldsModelField, we need it in a sec
        $mvcFactory = $app->bootComponent('com_vmmapicon')->getMVCFactory();

        /** @var DatasetModel $datasetModel */
        $datasetModel = $mvcFactory->createModel('Dataset', 'Site', ['ignore_request' => true]);

        $currentCategory = 0;

        if(is_object($datasetModel->getItem($datasetId)))
        {
            $currentCategory = $datasetModel->getItem($datasetId)->catid;
        }



        return [
            'fields' => [

            ],
            'metadata' => [
                'type' => true,
                'label' => 'Api',
                'value' => '', // Falls benötigt
            ]
        ];
    }

    public static function images($data)
    {
        $attachments = $data['attachments'];
        $attachments = json_decode($attachments, true);

        return $attachments;
    }
}
