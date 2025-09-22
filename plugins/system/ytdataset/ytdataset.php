<?php
/**
 * @package    plg_system_ytdataset
 *
 * @author     Kicktemp GmbH <hello@kicktemp.com>
 * @copyright  Copyright © 2020 Kicktemp GmbH. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://kicktemp.com
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use YOOtheme\Application;

defined('_JEXEC') or die;

/**
 * StudiogongDataset plugin.
 *
 * @package   plg_system_ytdataset
 * @since     1.0.0
 */
class plgSystemYtdataset extends CMSPlugin
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

        // Load a single module from the same directory
        $app = Application::getInstance();
        $app->load(__DIR__ . '/bootstrap.php');
    }

}
