<?php
namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Villaester\Component\Vmmapicon\Site\Helper\ApiHelper;

class ApisingleModel extends ApiitemModel
{
    protected $_context = 'com_vmmapicon.apisingle';

    protected function populateState()
    {
        parent::populateState();
        $app = Factory::getApplication();
        $this->setState('item.itemId', $app->input->getString('itemId', ''));
        // Context für YOOtheme & Listener bereitstellen
        $this->setState('context', 'com_vmmapicon.apisingle');
    }

    public function getItem($pk = null)
    {
        // Wenn itemId gesetzt ist, wähle das entsprechende Element aus data[]
        $itemId = (string) $this->getState('item.itemId');
        if ($itemId === '') {
            return parent::getItem($pk);
        }

        // Lade API-Konfig
        $pk = $pk ?: (int) $this->getState('api.id');
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('a.id, a.title, a.api_url, a.api_method, a.api_params, a.published')
            ->from('#__vmmapicon_apis AS a')
            ->where('a.id = :id')
            ->bind(':id', $pk, \Joomla\Database\ParameterType::INTEGER);
        $db->setQuery($query);
        $api = $db->loadObject();
        if (!$api || (int) $api->published !== 1) {
            throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
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
            if (isset($rec['id']) && (string) $rec['id'] === $itemId) {
                return $rec;
            }
        }
        return null;
    }
}
