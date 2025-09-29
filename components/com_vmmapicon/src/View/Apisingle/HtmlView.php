<?php
namespace Villaester\Component\Vmmapicon\Site\View\Apisingle;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
{
    protected $item;
    protected $params;
    protected $state;
    protected $pageclass_sfx = '';

    protected $context = 'com_vmmapicon.apisingle';

    public function display($tpl = null): void
    {
        $this->item   = $this->get('Item');
        $this->state  = $this->get('State');
        $this->params = $this->state->get('params');

        parent::display($tpl);
    }
}
