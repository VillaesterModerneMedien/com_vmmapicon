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

use Joomla\Plugin\System\Ytvmmapicon\Type\ApisQueryType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApisType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiQueryType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiType;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiimagesType;


class LoadSourceTypes
{
    public function handle($source): void
    {
        $query = [
            ApiQueryType::config(),
            ApisQueryType::config(),

        ];

        $types = [
            ['Api', ApiType::config()],
            ['Apis', ApisType::config()],
            ['Apiimages', ApiimagesType::config()],
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }
}
