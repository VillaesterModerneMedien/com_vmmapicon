<?php
/**
 * VM Map Icon Component
 *
 * @package     Joomla.Component
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * ApiResult controller class.
 *
 * @since  1.0.0
 */
class ApiresultController extends FormController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  1.0.0
     */
    protected $text_prefix = 'COM_VMMAPICON_APIRESULT';

    /**
     * Method to save a record - disabled for API results
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   1.0.0
     */
    public function save($key = null, $urlVar = null)
    {
        // API results are read-only
        $this->setMessage('COM_VMMAPICON_APIRESULT_READONLY', 'warning');
        $this->setRedirect('index.php?option=com_vmmapicon&view=apiresults');
        
        return false;
    }

    /**
     * Method to cancel an edit - redirect to list
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     *
     * @since   1.0.0
     */
    public function cancel($key = null)
    {
        $this->setRedirect('index.php?option=com_vmmapicon&view=apiresults');
        
        return true;
    }
}
