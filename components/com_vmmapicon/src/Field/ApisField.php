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

namespace Villaester\Component\Vmmapicon\Site\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Supports an HTML select list of APIs
 *
 * @since  1.0.0
 */
class ApisField extends ListField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0.0
     */
    protected $type = 'Apis';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   1.0.0
     */
    protected function getOptions()
    {
        $options = [];

        // Add a default option
        $options[] = HTMLHelper::_('select.option', '', Text::_('COM_VMMAPICON_FIELD_API_SELECT'));

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select(['id', 'title'])
                ->from('#__vmmapicon_apis')
                ->where('published = 1')
                ->order('title ASC');

            $db->setQuery($query);
            $apis = $db->loadObjectList();

            foreach ($apis as $api) {
                $options[] = HTMLHelper::_('select.option', $api->id, $api->id . ' - ' . $api->title);
            }
        } catch (\Exception $e) {
            // If there's an error, just return the default option
            Factory::getApplication()->enqueueMessage(Text::_('COM_VMMAPICON_ERROR_LOADING_APIS'), 'warning');
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
