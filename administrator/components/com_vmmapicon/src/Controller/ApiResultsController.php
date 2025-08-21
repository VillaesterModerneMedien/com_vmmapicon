<?php
/**
 *
 *
 * \ \    / /  \/  |  \/  |
 *  \ \  / /| \  / | \  / |
 *   \ \/ / | |\/| | |\/| |
 *    \  /  | |  | | |  | |
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH * * @package Joomla.Component
 * @subpackage  com_vmmapico
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Administrator\Controller;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * ApiResults list controller class.
 *
 * @since  1.0.0
 */
class ApiResultsController extends AdminController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  1.0.0
     */
    protected $text_prefix = 'COM_VMMAPICON_APIRESULTS';

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   1.0.0
     */
    public function getModel($name = 'Apiresult', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Method to delete items - disabled for API results
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function delete()
    {
        $this->setMessage(Text::_('COM_VMMAPICON_APIRESULTS_DELETE_NOT_ALLOWED'), 'warning');
        $this->setRedirect('index.php?option=com_vmmapicon&view=apiresults');
    }

    /**
     * Method to publish items - disabled for API results
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function publish()
    {
        $this->setMessage(Text::_('COM_VMMAPICON_APIRESULTS_PUBLISH_NOT_ALLOWED'), 'warning');
        $this->setRedirect('index.php?option=com_vmmapicon&view=apiresults');
    }

    /**
     * Method to refresh API results
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function refresh()
    {
        $this->setMessage(Text::_('COM_VMMAPICON_APIRESULTS_REFRESHED'), 'message');
        $this->setRedirect('index.php?option=com_vmmapicon&view=apiresults');
    }
}
