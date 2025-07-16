<?php
/**
 * VM Map Icon Component
 *
 * @package     Joomla.Component
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\Database\QueryInterface;

\defined('_JEXEC') or die;

/**
 * ApiResults list model.
 *
 * @since  1.0.0
 */
class ApiresultsModel extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since   1.0.0
     */
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'title', 'a.title',
                'url', 'a.url',
                'method', 'a.method',
            ];
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function populateState($ordering = 'a.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  QueryInterface
     *
     * @since   1.0.0
     */
    protected function getListQuery()
    {
        // Get available APIs
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select('a.id, a.title, a.url, a.method, a.params')
              ->from($db->quoteName('#__vmmapicon_apis', 'a'))
              ->where($db->quoteName('a.published') . ' = 1');

        // Filter by search
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('(' . $db->quoteName('a.title') . ' LIKE ' . $search . ')');
        }

        // Add the list ordering clause
        $orderCol = $this->state->get('list.ordering', 'a.id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

    /**
     * Method to get an array of data items with API results.
     *
     * @return  mixed  An array of data items on success, false on failure.
     *
     * @since   1.0.0
     */
    public function getItems()
    {
        $apis = parent::getItems();
        $results = [];

        if (!empty($apis)) {
            foreach ($apis as $api) {
                $result = new CMSObject();
                $result->id = $api->id;
                $result->api_id = $api->id;
                $result->api_title = $api->title;
                $result->api_url = $api->url;
                $result->api_method = $api->method;
                
                // Make API call
                $apiResponse = $this->makeApiCall($api);
                $result->response_data = $apiResponse;
                $result->response_status = $apiResponse !== false ? 'success' : 'error';
                $result->response_time = date('Y-m-d H:i:s');
                
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * Make API call based on configuration
     *
     * @param   object  $apiConfig  The API configuration
     *
     * @return  mixed  API response or false on failure
     *
     * @since   1.0.0
     */
    protected function makeApiCall($apiConfig)
    {
        try {
            $http = Factory::getHttp();
            $options = [];
            
            // Set headers if configured
            if (!empty($apiConfig->params)) {
                $params = json_decode($apiConfig->params, true);
                if (is_array($params)) {
                    foreach ($params as $param) {
                        if ($param['position'] === 'head' && !empty($param['key']) && !empty($param['value'])) {
                            $options['headers'][$param['key']] = $param['value'];
                        }
                    }
                }
            }

            // Make the API call
            $method = strtoupper($apiConfig->method ?? 'GET');
            
            switch ($method) {
                case 'POST':
                    $response = $http->post($apiConfig->url, '', $options);
                    break;
                case 'PUT':
                    $response = $http->put($apiConfig->url, '', $options);
                    break;
                case 'DELETE':
                    $response = $http->delete($apiConfig->url, $options);
                    break;
                default:
                    $response = $http->get($apiConfig->url, $options);
                    break;
            }

            if ($response->code >= 200 && $response->code < 300) {
                return json_decode($response->body, true) ?: $response->body;
            }

            return false;
        } catch (\Exception $e) {
            Factory::getApplication()->enqueueMessage(
                Text::sprintf('COM_VMMAPICON_API_ERROR', $e->getMessage()),
                'error'
            );
            return false;
        }
    }
}
