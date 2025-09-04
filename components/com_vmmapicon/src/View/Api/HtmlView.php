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

namespace Villaester\Component\Vmmapicon\Site\View\Api;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * HTML View class for the Api component
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The api item
     *
     * @var    object
     * @since  1.0.0
     */
    protected $item;

    /**
     * The page parameters
     *
     * @var    \Joomla\Registry\Registry|null
     * @since  1.0.0
     */
    protected $params;

    /**
     * The model state
     *
     * @var    object
     * @since  1.0.0
     */
    protected $state;

    /**
     * The page class suffix
     *
     * @var    string
     * @since  1.0.0
     */
    protected $pageclass_sfx = '';

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function display($tpl = null): void
    {
        $app = Factory::getApplication();

        $this->item   = $this->get('Item');
        $this->state  = $this->get('State');
        $this->params = $this->state->get('params');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        // Check if the API exists
        if (empty($this->item)) {
            throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
        }

        // Check if the API is published
        if ($this->item->published != 1) {
            throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_PUBLISHED'), 403);
        }

        $this->prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function prepareDocument(): void
    {
        $app     = Factory::getApplication();
        $menu    = $app->getMenu()->getActive();
        $pathway = $app->getPathway();
        $title   = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', Text::_('COM_VMMAPICON_API'));
        }

        $title = $this->params->get('page_title', '');

        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }

        // Add breadcrumb
        if (!$menu) {
            $pathway->addItem($this->item->title);
        }
    }
}
