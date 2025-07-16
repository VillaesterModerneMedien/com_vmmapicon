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
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * ApiResult admin model.
 *
 * @since  1.0.0
 */
class ApiresultModel extends AdminModel
{
    /**
     * The type alias for this content type.
     *
     * @var      string
     * @since    1.0.0
     */
    public $typeAlias = 'com_vmmapicon.apiresult';

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.0.0
     */
    protected $text_prefix = 'COM_VMMAPICON_APIRESULT';

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since   1.0.0
     */
    public function getForm($data = [], $loadData = true)
    {
        // This is a virtual entity, no form needed for API results
        return false;
    }

    /**
     * Method to get a single record from API.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  CMSObject|boolean  Object on success, false on failure.
     *
     * @since   1.0.0
     */
    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');

        if ($pk > 0) {
            // Get API configuration
            $apiModel = $this->getMVCFactory()->createModel('Api', 'Administrator');
            $apiConfig = $apiModel->getItem($pk);

            if (!$apiConfig || empty($apiConfig->url)) {
                return false;
            }

            // Make API call
            $result = $this->makeApiCall($apiConfig);
            
            if ($result !== false) {
                $item = new CMSObject();
                $item->id = $pk;
                $item->api_id = $pk;
                $item->api_title = $apiConfig->title;
                $item->api_url = $apiConfig->url;
                $item->response_data = $result;
                $item->response_time = date('Y-m-d H:i:s');
                
                return $item;
            }
        }

        return false;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.0.0
     */
    protected function loadFormData()
    {
        // This is a virtual entity, no form data needed
        return [];
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

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.0.0
     */
    public function save($data)
    {
        // API results are read-only, no saving allowed
        return false;
    }

    /**
     * Method to delete one or more records.
     *
     * @param   array  &$pks  An array of record primary keys.
     *
     * @return  boolean  True if successful, false if an error occurs.
     *
     * @since   1.0.0
     */
    public function delete(&$pks)
    {
        // API results are virtual, no deletion allowed
        return false;
    }
}
