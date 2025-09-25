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

    public function display($tpl = null): void
    {
        $this->item   = $this->get('Item');
        $this->state  = $this->get('State');
        $this->params = $this->state->get('params');

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        if ($this->item === null) {
            throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
        }

        parent::display($tpl);
    }
}

