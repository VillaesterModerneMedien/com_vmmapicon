<?php

namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use function YOOtheme\trans;

class ImagesType
{
    /**
     * @return array
     */
    public static function config()
    {

        $b = 'test';

        return [
            'fields' => [
                'image_intro' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Intro Image'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::image',
                    ],
                ],
                'image_title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans(' Image Titlex'),
                    ],
                    'extensions' => [

                        'call' => __CLASS__ . '::image',
                    ],
                ],
            ],
        ];
    }

    public static function image($data, $args, $context, $info)
    {
        echo '<pre>';
        var_dump('kikiki');die;
        echo '</pre>';
        $test = $data;
        return $data->{$info->fieldName} ?? null;
    }
}
