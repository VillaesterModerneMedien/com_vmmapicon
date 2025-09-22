<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @license     GNU General Public License version 2 or later
 */

// Main plugin class must be in global namespace for Joomla to instantiate
use Joomla\CMS\Plugin\CMSPlugin;
use YOOtheme\Application;

defined('_JEXEC') or die;

class PlgSystemVmmapiconyt extends CMSPlugin
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
		// Try registration early for backend
		if ($this->app->isClient('administrator')) {
			$this->registerWithYooTheme();
		}
	}

	/**
	 * onAfterRoute - Try registration after routing
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterRoute()
	{
		// Try registration for frontend and as fallback
		$this->registerWithYooTheme();
	}

	/**
	 * onBeforeCompileHead - Last chance to register
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onBeforeCompileHead()
	{
		// Final attempt to register with YooTheme
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

		// Check if YOOtheme Pro is loaded - try autoload if not
		if (!class_exists(Application::class, false)) {
			// Try to find YooTheme in common locations
			$paths = [
				JPATH_ROOT . '/templates/yootheme/vendor/yootheme/theme/src/Application.php',
				JPATH_ROOT . '/templates/yootheme_gantry/vendor/yootheme/theme/src/Application.php',
				JPATH_ROOT . '/templates/yootheme/vendor/yootheme/application.php',
			];

			foreach ($paths as $path) {
				if (file_exists($path)) {
					require_once $path;
					break;
				}
			}

			// Check again after trying to load
			if (!class_exists(Application::class, false)) {
				return;
			}
		}

		// Debug logging
		if (defined('JDEBUG') && JDEBUG) {
			error_log('VMMapiconYT: YooTheme Application found, registering provider');
		}

		try {
			$app = Application::getInstance();
			$app->load(__DIR__ . '/bootstrap.php');
			$registered = true;

			if (defined('JDEBUG') && JDEBUG) {
				error_log('VMMapiconYT: Provider registered successfully');
			}
		} catch (\Exception $e) {
			if (defined('JDEBUG') && JDEBUG) {
				error_log('VMMapiconYT: Registration failed - ' . $e->getMessage());
			}
		}
	}
}
