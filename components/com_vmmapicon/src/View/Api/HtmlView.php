<?php
/**
 * @package     Joomla.Component
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @license     GNU General Public License version 2 or later
 */

namespace Villaester\Component\Vmmapicon\Site\View\Api;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * Single API View
 *
 * @since 1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Die API-Antwort
     *
     * @var mixed
     */
    protected $item;

    /**
     * Anzeige der View
     *
     * @param   string|null  $tpl  Template Name
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        $this->item = $this->get('Item');

        parent::display($tpl);
    }
}



