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

	private function _setMappings($idMapping){
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$apiId = (int) $this->getState('api.id');

		if (empty($idMapping) || !$apiId) {
			return;
		}

		try {
			$db->transactionStart();

			$query = $db->getQuery(true)
				->delete($db->quoteName('#__vmmapicon_mapping'))
				->where($db->quoteName('api_id') . ' = ' . $apiId);
			$db->setQuery($query)->execute();

			$table   = $db->quoteName('#__vmmapicon_mapping');
			$columns = [
				$db->quoteName('api_id'),
				$db->quoteName('article_id'),
				$db->quoteName('alias'),
			];

			$rows = [];
			foreach ($idMapping as $articleId => $alias) {
				$rows[] = $apiId . ', ' . (int) $articleId . ', ' . $db->quote((string) $alias);
			}

			$chunkSize = 500;
			foreach (array_chunk($rows, $chunkSize) as $chunk) {
				$query = $db->getQuery(true)
					->insert($table)
					->columns($columns);
				foreach ($chunk as $row) {
					$query->values($row);
				}
				$db->setQuery($query)->execute();
			}

			$db->transactionCommit();
		} catch (\Throwable $e) {
			$db->transactionRollback();
			throw $e;
		}
	}



    public function getItems($api = null, $limit = null, $start = null, String $template = null)
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

        $raw = ApiHelper::getApiResult($cfg);
	    if (!$raw) { return []; }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return [];
        }

		$itemIndex = 0;
		$idMapping = [];

		foreach ($decoded['data'] as $item) {
			$categoryAlias = $decoded['data'][$itemIndex]['attributes']['categoryalias'];
			$decoded['data'][$itemIndex]['attributes']['self_link'] = "index.php?option=com_vmmapicon&view=apisingle&id=" . $id . "&articleId=" . $item['id'] . '&alias=' . $item['attributes']['alias'] . '&Itemid=' . $Itemid . '&category=' . $categoryAlias;
			$idMapping[$item['id']] = $item['attributes']['alias'];
		$itemIndex++;
		}
        $list = $decoded['data'] ?? [];
        if (!is_array($list)) { return []; }

	    $total = count($list);
	    $this->setState('list.total', $total);

	    if ($limit === 0) {
		    return $list;
	    }

		$items  = array_slice($list, $start, $limit);
		$this->_setMappings($idMapping);
	    return $items;
    }

	public function getTotal(): int
	{
		$total = (int) $this->getState('list.total', 0);
		if ($total === 0) {
			$total = count($this->getItems());
			$this->setState('list.total', $total);
		}

		return $total;
	}
	public function getPagination(): Pagination
	{
		return new Pagination(
			$this->getTotal(),
			(int) $this->getState('list.start', 0),
			(int) $this->getState('list.limit', 0)
		);
	}


}
