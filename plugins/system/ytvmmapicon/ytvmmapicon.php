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
* Api-Dataset plugin.
*
* @package   plg_system_ytvmmapicon
* @since     1.0.0
*/
class plgSystemYtvmmapicon extends CMSPlugin
{
	protected $autoloadLanguage = true;
	protected $app;
	protected $db;

	/**
	 * onAfterInitialise - Try early registration
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterInitialise()
	{
		$this->registerWithYooTheme();
	}

	/**
	 * onAfterRoute - Try again after routing
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterRoute()
	{
		$this->registerWithYooTheme();
	}

	/**
	 * Register with YooTheme
	 *
	 * @return  void
	 */
	protected function registerWithYooTheme()
	{
		static $registered = false;

		if ($registered) {
			return;
		}

		// Check if YOOtheme Pro is loaded
		if (!class_exists(Application::class, false)) {
			return;
		}

		// Debug logging
		if (defined('JDEBUG') && JDEBUG) {
			error_log('YTVMMapicon: YooTheme Application found, registering provider');
		}

		try {
			$app = Application::getInstance();
			$app->load(__DIR__ . '/bootstrap.php');
			$registered = true;

			if (defined('JDEBUG') && JDEBUG) {
				error_log('YTVMMapicon: Provider registered successfully');
			}
		} catch (\Exception $e) {
			if (defined('JDEBUG') && JDEBUG) {
				error_log('YTVMMapicon: Registration failed - ' . $e->getMessage());
			}
		}
	}
}
