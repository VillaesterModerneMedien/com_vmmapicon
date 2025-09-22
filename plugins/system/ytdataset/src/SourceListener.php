<?php

use Joomla\CMS\Table\ContentType;
use  Joomla\Plugin\System\Ytdataset\Type\MyType;
use Joomla\Plugin\System\Ytdataset\Type\DatasetType;
	use YOOtheme\Builder\Source;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Url;

class SourceListener
{
    /**
     * @param Source $source
     */
    public static function initSource($source)
    {
        $source->objectType('DatasetType', DatasetType::config());
        $source->objectType('MyType', MyType::config());
        $source->queryType(DatasetQueryType::config());
    }

    public static function initCustomizer(Config $config, Metadata $metadata)
    {
        $config->add('customizer.dataset', array(
            [
                'value' => 1,
                'text' => 'text',
            ],
        ));

        $config->add('customizer.templates', [

            'com_vmmdatabase.dataset' => [
                'label' => 'Dataset'
            ],

        ]);

        $metadata->set('script:customizer.dataset', ['src' => Url::to('plugins/system/ytdataset/dataset.js'), 'defer' => true]);
    }
}
