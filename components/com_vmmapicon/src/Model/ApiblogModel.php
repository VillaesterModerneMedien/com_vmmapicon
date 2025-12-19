<?php
namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;
use Villaester\Component\Vmmapicon\Site\Helper\ApiHelper;
use Joomla\CMS\Pagination\Pagination;


class ApiblogModel extends ListModel
{
    protected $_context = 'com_vmmapicon.apiblog';

    protected function populateState($ordering = null, $direction = null)
    {
	    parent::populateState($ordering, $direction);

        $app = Factory::getApplication();

        $id = $app->input->getInt('id');

	    $menu = $app->getMenu();
	    $active = $menu->getActive();
	    $paramsMenu = $active->getParams();

	    $this->setState('api.id', $id);
        $this->setState('params', $app->getParams());
        $this->setState('context', 'com_vmmapicon.apiblog');
        $this->setState('Itemid', $active->id);

		$pageSize = $paramsMenu->get('pagesize', 10);
	    $this->setState('list.limit', $pageSize);

	    $start = $app->input->getInt('limitstart', null);
	    if ($start === null) {
		    $start = $app->input->getInt('start', null);
	    }
	    if ($start === null) {
		    $page  = max(1, $app->input->getInt('page', 1));
		    $start = ($page - 1) * $pageSize;
	    }
	    $this->setState('list.start', max(0, (int) $start));

    }

    public function getItems($api = null, $limit = null, $start = null, bool $singleTemplate = false)
    {
		$Itemid = $this->getState('Itemid');
        $id = (int) $this->getState('api.id');
        if (!$id && $api === null) {
            return [];
        }
		if($api !== null) {
			$id = $api;
		}

		if(!$start){
	        $start = (int) $this->getState('list.start');
		}
		if(!$limit){
	        $limit = (int) $this->getState('list.limit');
		}

	    // Lade API-Konfiguration
        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $cfg = $model->getItem($id);
        if (!$cfg) {
            return [];
        }

        $raw = ApiHelper::getApiResult($cfg, $start, $limit, $singleTemplate);
	    if (!$raw) { return []; }

        $decoded = json_decode($raw, true);

		$pages = $decoded['meta']['total-pages'];
		$total =$pages * $limit;
		$this->setState('list.total', $total);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return [];
        }

        $list = $decoded['data'] ?? [];
        if (!is_array($list)) { return []; }

	    return $list;
    }

	public function getPagination(): Pagination
	{
		return new Pagination(
			$this->getState('list.total'),
			(int) $this->getState('list.start', 0),
			(int) $this->getState('list.limit', 0)
		);
	}


}
