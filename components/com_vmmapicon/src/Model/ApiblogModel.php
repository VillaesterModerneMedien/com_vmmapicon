<?php
namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Villaester\Component\Vmmapicon\Site\Helper\ApiHelper;

class ApiblogModel extends ListModel
{
    protected $_context = 'com_vmmapicon.apiblog';

    protected function populateState($ordering = null, $direction = null)
    {
        $app = Factory::getApplication();
        $id = $app->input->getInt('id');
        $this->setState('api.id', $id);
        $this->setState('params', $app->getParams());
    }

    public function getItems()
    {
        $id = (int) $this->getState('api.id');
        if (!$id) {
            return [];
        }
        // Lade API-Konfiguration
        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $cfg = $model->getItem($id);
        if (!$cfg) {
            return [];
        }
        $raw = ApiHelper::getApiResult($cfg);
        if (!$raw) { return []; }
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return [];
        }
        $list = $decoded['data'] ?? [];
        if (!is_array($list)) { return []; }
        return $list;
    }
}
