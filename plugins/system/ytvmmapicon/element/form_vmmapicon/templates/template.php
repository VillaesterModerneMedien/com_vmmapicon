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

    use Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Plugin\PluginHelper;

    $form = '{jtf mailto=' . $props['exposeeContact'] . ' | subject=' . $props['exposeeTitle'] . ' (' . $props['exposeeId'] . ' ) | theme=exposee }';

    if (PluginHelper::isEnabled('content', 'jtf'))
    {
       echo HTMLHelper::_('content.prepare', $form);
    }
