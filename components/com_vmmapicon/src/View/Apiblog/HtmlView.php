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
    protected $params;

    public function display($tpl = null): void
    {
        $this->items      = $this->get('Items');
        $this->state      = $this->get('State');
        $this->params     = $this->state->get('params');

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        parent::display($tpl);
    }
}
