<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.vmmapiconyt
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @license     GNU General Public License version 2 or later
 */

namespace Villaester\Plugin\System\Vmmapiconyt;

use Joomla\CMS\Plugin\CMSPlugin;
use YOOtheme\Application;

defined('_JEXEC') or die;

class PlgSystemVmmapiconyt extends CMSPlugin
{
    protected $autoloadLanguage = true;
    protected $app;
    protected $db;

    /**
     * Runs after the framework has loaded and the application initialise method has been called
     */
    public function onAfterInitialise()
    {
        // Debug: Log that plugin is being executed
        error_log('VMMapiconYT Plugin: onAfterInitialise called');

        // Check if YOOtheme Pro is loaded
        if (!class_exists(Application::class, false)) {
            error_log('VMMapiconYT Plugin: YOOtheme Application class not found');
            return;
        }

        error_log('VMMapiconYT Plugin: YOOtheme Application class found, loading bootstrap');

        // Load a single module from the same directory
        $app = Application::getInstance();
        $result = $app->load(__DIR__ . '/bootstrap.php');

        error_log('VMMapiconYT Plugin: Bootstrap loaded with result: ' . ($result ? 'success' : 'failed'));
    }
}