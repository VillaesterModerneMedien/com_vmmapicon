<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 */

namespace Villaester\Plugin\System\Vmmapiconyt\Listener;

use YOOtheme\Builder\Source;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Url;
use Villaester\Plugin\System\Vmmapiconyt\Type\VmmapiconApiType;
use Villaester\Plugin\System\Vmmapiconyt\Type\VmmapiconApiQueryType;

class SourceListener
{
    /**
     * Initialize source types
     *
     * @param Source $source
     */
    public static function initSource($source)
    {
        error_log('VMMapiconYT SourceListener::initSource called (static)');

        // Register object types with dynamic fields
        $source->objectType('VmmapiconApi', VmmapiconApiType::config());

        // Register query types
        $source->queryType(VmmapiconApiQueryType::config());

        error_log('VMMapiconYT SourceListener: Types registered successfully');
    }

    /**
     * Initialize customizer
     *
     * @param Config $config
     * @param Metadata $metadata
     */
    public static function initCustomizer(Config $config, Metadata $metadata)
    {
        error_log('VMMapiconYT SourceListener::initCustomizer called (static)');

        // Add customizer configuration for API sources
        $config->add('customizer.vmmapicon', [
            'vmmapicon_source' => [
                'label' => 'API Source',
                'description' => 'Select an API configuration to use as data source',
                'type' => 'select',
                'default' => '',
                'options' => self::getApiOptions()
            ]
        ]);

        // Add templates configuration
        $config->add('customizer.templates', [
            'com_vmmapicon.api' => [
                'label' => 'VMMapicon API'
            ]
        ]);

        // Add JavaScript for customizer
        $metadata->set('script:customizer.vmmapicon', [
            'src' => Url::to('plugins/system/vmmapiconyt/js/vmmapicon.js'),
            'defer' => true
        ]);

        error_log('VMMapiconYT SourceListener::initCustomizer completed');
    }    /**
     * Get available API options for customizer
     *
     * @return array
     */
    protected static function getApiOptions()
    {
        $options = ['' => 'None'];

        try {
            $db = \Joomla\CMS\Factory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName(['id', 'title']))
                ->from($db->quoteName('#__vmmapicon_apis'))
                ->where($db->quoteName('published') . ' = 1')
                ->order($db->quoteName('title') . ' ASC');

            $db->setQuery($query);
            $apis = $db->loadObjectList();

            foreach ($apis as $api) {
                $options[$api->id] = $api->title;
            }

        } catch (\Exception $e) {
            error_log('VMMapiconYT: Error loading API options: ' . $e->getMessage());
        }

        return $options;
    }
}
