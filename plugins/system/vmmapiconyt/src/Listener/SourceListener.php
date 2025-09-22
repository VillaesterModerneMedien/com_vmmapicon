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
    public function initSource($source)
    {
        if (defined('JDEBUG') && JDEBUG) {
            error_log('VMMapiconYT: initSource called - source class: ' . get_class($source));
        }

        try {
            // First register a simple test type to verify registration works
            $source->objectType('VmmapiconTest', \Villaester\Plugin\System\Vmmapiconyt\Type\TestType::config());

            // Register object types with dynamic fields
            $source->objectType('VmmapiconApi', VmmapiconApiType::config());

            // Register query types
            $source->queryType(VmmapiconApiQueryType::config());

            // Also try to register a simple test query
            $source->queryType([
                'fields' => [
                    'vmmapiconTest' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => 'VMMapicon Test Query',
                            'group' => 'VMMapicon'
                        ],
                        'extensions' => [
                            'call' => function() {
                                return 'VMMapicon is working!';
                            }
                        ]
                    ]
                ]
            ]);

            if (defined('JDEBUG') && JDEBUG) {
                error_log('VMMapiconYT: Source types registered successfully');
            }
        } catch (\Exception $e) {
            if (defined('JDEBUG') && JDEBUG) {
                error_log('VMMapiconYT: Error registering source types - ' . $e->getMessage());
                error_log('VMMapiconYT: Stack trace - ' . $e->getTraceAsString());
            }
        }
    }

    /**
     * Initialize customizer
     *
     * @param Config $config
     * @param Metadata $metadata
     */
    public function initCustomizer(Config $config, Metadata $metadata)
    {
        // Align with ytdataset SourceListener structure
        $config->add('customizer.vmmapicon', array(
            [
                'value' => 1,
                'text'  => 'text',
            ],
        ));

        $config->add('customizer.templates', [
            'com_vmmapicon.apiitem' => [
                'label' => 'VMMapicon API Item'
            ],
        ]);

        $metadata->set('script:customizer.vmmapicon', [
            'src'   => Url::to('plugins/system/vmmapiconyt/js/vmmapicon.js'),
            'defer' => true,
        ]);
    }    /**
     * Get available API options for customizer
     *
     * @return array
     */
    protected static function getApiOptions()
    {
        // Return list of option objects: [{value: '', text: 'None'}, ...]
        $options = [ [ 'value' => '', 'text' => 'None' ] ];

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
                $options[] = [ 'value' => (string) $api->id, 'text' => $api->title ];
            }

        } catch (\Exception $e) {
            error_log('VMMapiconYT: Error loading API options: ' . $e->getMessage());
        }

        return $options;
    }
}
