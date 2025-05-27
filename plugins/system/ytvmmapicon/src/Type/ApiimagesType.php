<?php

    namespace Joomla\Plugin\System\Ytvmmapicon\Type;

    use function YOOtheme\trans;

    class ApiimagesType
    {
        /**
         * @return array
         */
        public static function config()
        {
            return [
                'fields' => [
                    'image_title' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => trans('Image-Title'),
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::title',
                        ],
                    ],
                    'original' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => trans('Image in original size'),
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::image',
                        ],

                    ],
                    'scale1000' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => trans('Image in medium size(1000px x 1000px)'),
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::image',
                        ],
                    ],
                    'scale400x300' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => trans('Image in small size(400px x 300px)'),
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::image',
                        ],
                    ],    ],
            ];
        }

        public static function image($data, $args, $context, $info)
        {
            $size = $info->fieldName;
            $url = $data['urls'][$size];
            return $url;
        }
        public static function title($data, $args, $context, $info)
        {
            return $data['title'];
        }
    }
