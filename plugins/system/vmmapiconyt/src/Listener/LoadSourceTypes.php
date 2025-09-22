<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 */

namespace Villaester\Plugin\System\Vmmapiconyt\Listener;

use Villaester\Plugin\System\Vmmapiconyt\Type\VmmapiconApiQueryType;
use Villaester\Plugin\System\Vmmapiconyt\Type\VmmapiconApiType;

class LoadSourceTypes
{
    /**
     * Handle source initialization
     *
     * @param $source
     */
    public function handle($source): void
    {
        // Debug: Log dass die Methode aufgerufen wurde
        error_log('VMMapicon LoadSourceTypes::handle wurde aufgerufen!');
        \Joomla\CMS\Factory::getApplication()->enqueueMessage('VMMapicon LoadSourceTypes::handle wurde aufgerufen!', 'notice');
        
        // Debug: Temporär Exception werfen um zu sehen ob es aufgerufen wird
        // throw new \Exception('LoadSourceTypes::handle wurde aufgerufen!');

        // Register query types
        $query = [
            VmmapiconApiQueryType::config()
        ];

        // Register object types
        $types = [
            ['VmmapiconApi', VmmapiconApiType::config()]
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }
}
