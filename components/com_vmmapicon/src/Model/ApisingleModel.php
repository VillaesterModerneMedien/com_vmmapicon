<?php
namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Villaester\Component\Vmmapicon\Site\Helper\ApiHelper;

class ApisingleModel extends ApiitemModel
{
    protected $_context = 'com_vmmapicon.apisingle';

    protected function populateState()
    {
        parent::populateState();
        $app = Factory::getApplication();
        $this->setState('item.articleId', $app->input->getString('articleId', ''));

        // Context für YOOtheme & Listener bereitstellen
        $this->setState('context', 'com_vmmapicon.apisingle');
    }

	public function getMapping($alias, $category){
		$db = Factory::getContainer()->get(DatabaseInterface::class);

		if ($alias === null || $alias === '' || !$category) {
			return 0;
		}

		$query = $db->getQuery(true)
			->select($db->quoteName('article_id'))
			->from($db->quoteName('#__vmmapicon_mapping'))
			->where($db->quoteName('alias') . ' = ' . $db->quote((string) $alias))
			->where($db->quoteName('category') . ' = ' . $db->quote((string) $category));

		// Ersten Treffer holen
		$db->setQuery($query, 0, 1);
		$articleId = $db->loadResult();

		return (int) $articleId;
	}

	public function getItem($id = null)
    {
        // Wenn itemId gesetzt ist, wähle das entsprechende Element aus data[]
        $articleId = (string) $this->getState('item.articleId');
        $apiId = (int) $this->getState('api.id');

		if ($articleId === '') {
			$articleId = $id;
        }

	    // Lade API-Konfiguration
	   $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
	   $api = $model->getItem($apiId);

	   if (!$api) {
		   return [];
	   }

        $raw = ApiHelper::getApiResult($api);
        if (!$raw) { return null; }
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return null;
        }
        $data = $decoded['data'] ?? [];
        if (!is_array($data)) { return null; }
        foreach ($data as $rec) {
            if (isset($rec['id']) && (string) $rec['id'] === $articleId) {
                return $rec;
            }
        }
        return null;
    }
}
