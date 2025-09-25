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
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiImage;

class SourceListener
{
    public static function initSource($source): void
    {
        $source->objectType('ApiImage', ApiImage::config());
        $source->objectType('ApiType', ApiType::config());
        $source->queryType(ApiQueryType::config());
    }

}
