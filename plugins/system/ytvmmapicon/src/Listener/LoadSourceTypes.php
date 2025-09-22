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



namespace Joomla\Plugin\System\Ytvmmapicon\Listener;

use Joomla\Plugin\System\Ytvmmapicon\Type\ApiQueryType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiType;


class LoadSourceTypes
{
    public function handle($source): void
    {
        if (defined('JDEBUG') && JDEBUG) {
            error_log('YTVMMapicon: LoadSourceTypes::handle called');
        }

        try {
            $query = [
                ApiQueryType::config(),
            ];

            $types = [
                ['Api', ApiType::config()],
            ];

            foreach ($query as $args) {
                $source->queryType($args);
            }

            foreach ($types as $args) {
                $source->objectType(...$args);
            }

            if (defined('JDEBUG') && JDEBUG) {
                error_log('YTVMMapicon: Source types registered successfully');
            }
        } catch (\Exception $e) {
            if (defined('JDEBUG') && JDEBUG) {
                error_log('YTVMMapicon: Error registering source types - ' . $e->getMessage());
            }
        }
    }
}
