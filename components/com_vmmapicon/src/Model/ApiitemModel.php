<?php
namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Villaester\Component\Vmmapicon\Site\Helper\ApiHelper;

class ApiitemModel extends ItemModel
{
    protected $_context = 'com_vmmapicon.apiitem';
    protected $apiConfig = null;

    protected function populateState()
    {
        $app = Factory::getApplication();
        $pk = $app->input->getInt('id');
        $this->setState('api.id', $pk);
        $this->setState('item.index', $app->input->getInt('index', 0));
        $this->setState('item.path', $app->input->getString('path', ''));
        $this->setState('params', $app->getParams());
        // Context für YOOtheme & Listener bereitstellen
        $this->setState('context', 'com_vmmapicon.apiitem');
    }

    public function getItem($pk = null)
    {
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

        // Keep config for later usage
        $this->apiConfig = $api;

        $raw = ApiHelper::getApiResult($api);
        if (!$raw) {
            return null;
        }
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        // Optional specific path (e.g., data->items)
        $path = $this->getState('item.path') ?: '';
        if ($path) {
            $decoded = $this->followPath($decoded, $path);
        }

        // If result is a list, pick index
        if (is_array($decoded) && array_is_list($decoded)) {
            $idx = max(0, (int) $this->getState('item.index'));
            return $decoded[$idx] ?? null;
        }

        // Common structure: { data: [...] }
        if (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            $idx = max(0, (int) $this->getState('item.index'));
            return $decoded['data'][$idx] ?? null;
        }

        // Otherwise return the object itself
        return $decoded;
    }

    public function getApiConfig()
    {
        if ($this->apiConfig) {
            return $this->apiConfig;
        }
        // Ensure config loaded
        $this->getItem();
        return $this->apiConfig;
    }

    private function followPath($data, string $path)
    {
        $parts = preg_split('/->|\./', $path) ?: [];
        $cur = $data;
        foreach ($parts as $p) {
            if ($p === '') continue;
            if (is_array($cur)) {
                $cur = $cur[$p] ?? null;
            } elseif (is_object($cur)) {
                $cur = $cur->$p ?? null;
            } else {
                return null;
            }
        }
        return $cur;
    }
}
