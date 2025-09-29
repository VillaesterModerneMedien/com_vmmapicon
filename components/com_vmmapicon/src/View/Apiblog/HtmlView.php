<?php
namespace Villaester\Component\Vmmapicon\Site\View\Apiblog;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
{
    protected $items;
    protected $state;
    protected $params = null;
	protected $pagination;


	protected $context = 'com_vmmapicon.apiblog';

    public function display($tpl = null): void
    {
        $this->items      = $this->get('Items');
        $this->state      = $this->get('State');
        $this->params     = $this->state->get('params');
	    $this->pagination = $this->get('Pagination');

        parent::display($tpl);
    }
}
