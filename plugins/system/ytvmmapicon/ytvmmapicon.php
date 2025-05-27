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


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use YOOtheme\Application;

defined('_JEXEC') or die;

/**
* StudiogongDataset plugin.
*
* @package   plg_system_ytdataset
* @since     1.0.0
*/
class plgSystemYtvmmapicon extends CMSPlugin
{
	/**
	 * onAfterInitialise.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterInitialise ()
	{
	    // Check if YOOtheme Pro is loaded
	    if (!class_exists(Application::class, false)) {
	        return;
	    }

	    $b = Factory::getApplication();

	    // Load a single module from the same directory
	    $app = Application::getInstance();
	    $app->load(__DIR__ . '/bootstrap.php');
	}

}
