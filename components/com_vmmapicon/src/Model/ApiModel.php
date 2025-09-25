<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien GmbH
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Api Model
 *
 * @since  1.0.0
 */
class ApiModel extends ItemModel
{
    /**
     * Model context string.
     *
     * @var    string
     * @since  1.0.0
     */
    protected $_context = 'com_vmmapicon.api';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function populateState()
    {
        $app = Factory::getApplication();

        // Load state from the request.
        $pk = $app->input->getInt('id');
        $this->setState('api.id', $pk);

        $offset = $app->input->getUint('limitstart');
        $this->setState('list.offset', $offset);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);
    }

    /**
     * Method to get api data.
     *
     * @param   integer  $pk  The id of the api.
     *
     * @return  object|boolean  Menu item data object on success, false on failure.
     *
     * @since   1.0.0
     */
    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('api.id');

        if ($this->_item === null) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            try {
                $db = $this->getDbo();
                $query = $db->getQuery(true)
                    ->select(
                        $this->getState(
                            'item.select',
                            'a.id, a.title, a.alias, a.api_url, a.api_method, a.api_params, ' .
                            'a.published, a.created, a.created_by, a.modified, a.modified_by, a.access, ' .
                            'a.params, a.metakey, a.metadesc, a.metadata'
                        )
                    )
                    ->from('#__vmmapicon_apis AS a')
                    ->where('a.id = :id')
                    ->where('a.published = 1')
                    ->bind(':id', $pk, \Joomla\Database\ParameterType::INTEGER);

                $db->setQuery($query);

                $data = $db->loadObject();

                if (empty($data)) {
                    throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
                }

                // Convert parameter fields to objects.
                $registry = new Registry($data->params);
                $data->params = $registry->toArray();

                // Convert metadata field to object.
                $registry = new Registry($data->metadata);
                $data->metadata = $registry->toArray();

                $this->_item[$pk] = $data;
            } catch (\Exception $e) {
                if ($e->getCode() == 404) {
                    throw new \Exception(Text::_('COM_VMMAPICON_ERROR_API_NOT_FOUND'), 404);
                } else {
                    $this->setError($e);
                    $this->_item[$pk] = false;
                }
            }
        }

        return $this->_item[$pk];
    }

    /**
     * Method to get a list of APIs for selection
     *
     * @return  array  List of APIs
     *
     * @since   1.0.0
     */
    public function getApis()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select(['id', 'title'])
            ->from('#__vmmapicon_apis')
            ->where('published = 1')
            ->order('title ASC');

        $db->setQuery($query);
        $apis = $db->loadObjectList();

        $options = [];
        foreach ($apis as $api) {
            $options[] = [
                'value' => $api->id,
                'text' => $api->id . ' - ' . $api->title
            ];
        }
        return $options;
    }
}
