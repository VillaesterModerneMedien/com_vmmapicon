<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @license     GNU General Public License version 2 or later
 */

namespace Villaester\Component\Vmmapicon\Administrator\View\Apis;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Button\DropdownButton;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for a list of APIs.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * An array of items
     *
     * @var  array
     */
    protected $items;

    /**
     * The pagination object
     *
     * @var  Pagination
     */
    protected $pagination;

    /**
     * The model state
     *
     * @var  CMSObject
     */
    protected $state;

    /**
     * Form object for search filters
     *
     * @var  Form
     */
    public $filterForm;

    /**
     * The active search filters
     *
     * @var  array
     */
    public $activeFilters;

    /**
     * Is this view an Empty State
     *
     * @var  boolean
     * @since 4.0.0
     */
    private $isEmptyState = false;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function display($tpl = null)
    {
        $this->items         = $this->get('Items');
        $this->pagination    = $this->get('Pagination');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (!\count($this->items) && $this->isEmptyState = $this->get('IsEmptyState')) {
            $this->setLayout('emptystate');
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function addToolbar()
    {
        $canDo = ContentHelper::getActions('com_vmmapicon');
        $user  = Factory::getApplication()->getIdentity();

        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        ToolbarHelper::title(Text::_('COM_VMMAPICON_MANAGER_APIS'), 'puzzle apis');

        if ($canDo->get('core.create')) {
            $toolbar->addNew('api.add');
        }

        if (!$this->isEmptyState && ($canDo->get('core.edit.state') || \count($this->transitions))) {
            /** @var DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('status-group')
                ->text('JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childBar = $dropdown->getChildToolbar();

            if ($canDo->get('core.edit.state')) {
                $childBar->publish('apis.publish')->listCheck(true);
                $childBar->unpublish('apis.unpublish')->listCheck(true);
                $childBar->archive('apis.archive')->listCheck(true);

                if ($this->state->get('filter.published') != -2) {
                    $childBar->trash('apis.trash')->listCheck(true);
                }
            }
        }

        if (!$this->isEmptyState && $this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            $toolbar->delete('apis.delete')
                ->text('JTOOLBAR_EMPTY_TRASH')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if ($canDo->get('core.admin')) {
            $toolbar->preferences('com_vmmapicon');
        }

        ToolbarHelper::help('', false, 'https://docs.joomla.org/Help4.x:Components_API_Manager');
    }
}