<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien GmbH
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Api controller class.
 *
 * @since  1.0.0
 */
class ApiController extends BaseController
{
    /**
     * Method to display an API
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe URL parameters
     *
     * @return  static  This object to support chaining.
     *
     * @since   1.0.0
     */
    public function display($cachable = false, $urlparams = [])
    {
        $cachable = true;

        // Set the default view name and format from the Request
        $viewName = $this->input->get('view', 'api');
        $this->input->set('view', $viewName);

        $id = $this->input->getInt('id');

        if (!$id) {
            throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
        }

        $user = Factory::getApplication()->getIdentity();

        if (!$user->get('guest')) {
            $cachable = false;
        }

        $safeurlparams = [
            'id' => 'INT',
            'return' => 'BASE64',
            'lang' => 'CMD'
        ];

        parent::display($cachable, $safeurlparams);

        return $this;
    }
}
