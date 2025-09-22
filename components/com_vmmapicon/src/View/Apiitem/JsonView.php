<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_vmmapicon
 */

namespace Villaester\Component\Vmmapicon\Site\View\Apiitem;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class JsonView extends BaseHtmlView
{
    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $response = [ 'error' => null, 'data' => null ];

        try {
            $model = $this->getModel();
            $item = $model->getItem();
            if ($item === null) {
                throw new \RuntimeException(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
            }
            $response['data'] = $item;
        } catch (\Throwable $e) {
            $response['error'] = [ 'message' => $e->getMessage(), 'code' => $e->getCode() ];
        }

        $app->setHeader('Content-Type', 'application/json; charset=utf-8', true);
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
        $app->close();
    }
}

