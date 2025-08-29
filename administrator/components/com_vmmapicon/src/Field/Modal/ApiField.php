<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_vmmapicon
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Villaester\Component\Vmmapicon\Administrator\Field\Modal;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ModalSelectField;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\ParameterType;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Supports a modal api picker.
 *
 * @since  1.6
 */
class ApiField extends FormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    protected $type = 'Modal_Api';


    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getInput()
    {
        $allowClear  = ((string) $this->element['clear'] != 'false');
        $allowSelect = ((string) $this->element['select'] != 'false');

        // The active dataset id field.
        $value = (int) $this->value > 0 ? (int) $this->value : '';

        // Create the modal id.
        $modalId = 'Dataset_' . $this->id;

        // Add the modal field script to the document head.
        HTMLHelper::_(
            'script',
            'system/fields/modal-fields.min.js',
            ['version' => 'auto', 'relative' => true]
        );

        // Script to proxy the select modal function to the modal-fields.js file.
        if ($allowSelect) {
            static $scriptSelect = null;

            if (is_null($scriptSelect)) {
                $scriptSelect = [];
            }

            if (!isset($scriptSelect[$this->id])) {
                Factory::getDocument()->addScriptDeclaration("
    function jSelectDataset_"
                    . $this->id
                    . "(id, title, object) { window.processModalSelect('Dataset', '"
                    . $this->id . "', id, title, '', object);}");

                $scriptSelect[$this->id] = true;
            }
        }

        // Setup variables for display.
        $linkVmmapicon = 'index.php?option=com_vmmapicon&amp;view=apis&amp;layout=modal&amp;tmpl=component&amp;'
            . Session::getFormToken() . '=1';
        $modalTitle   = Text::_('COM_VMMAPICON_CHANGE_DATASET');

        if (isset($this->element['language'])) {
            $linkVmmapicon .= '&amp;forcedLanguage=' . $this->element['language'];
            $modalTitle .= ' &#8212; ' . $this->element['label'];
        }

        $urlSelect = $linkVmmapicon . '&amp;function=jSelectDataset_' . $this->id;

        if ($value) {
            $db    = Factory::getContainer()->get('DatabaseDriver');
            $query = $db->getQuery(true)
                ->select($db->quoteName('title'))
                ->from($db->quoteName('#__vmmapicon_apis'))
                ->where($db->quoteName('id') . ' = ' . (int) $value);
            $db->setQuery($query);

            try {
                $title = $db->loadResult();
            } catch (\RuntimeException $e) {
                Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            }
        }

        $title = empty($title) ? Text::_('COM_VMMAPICON_SELECT_A_DATASET') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        // The current dataset display field.
        $html  = '';

        if ($allowSelect || $allowNew || $allowEdit || $allowClear) {
            $html .= '<span class="input-group">';
        }

        $html .= '<input class="form-control" id="' . $this->id . '_name" type="text" value="' . $title . '" readonly size="35">';

        // Select dataset button
        if ($allowSelect) {
            $html .= '<button'
                . ' class="btn btn-primary hasTooltip' . ($value ? ' hidden' : '') . '"'
                . ' id="' . $this->id . '_select"'
                . ' data-bs-toggle="modal"'
                . ' type="button"'
                . ' data-bs-target="#ModalSelect' . $modalId . '"'
                . ' title="' . HTMLHelper::tooltipText('COM_VMMAPICON_CHANGE_DATASET') . '">'
                . '<span class="icon-file" aria-hidden="true"> </span>' . Text::_('JSELECT')
            . '</button>';
  }

        // Clear dataset button
        if ($allowClear) {
            $html .= '<button'
                . ' class="btn btn-secondary' . ($value ? '' : ' hidden') . '"'
                . ' id="' . $this->id . '_clear"'
                . ' type="button"'
                . ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
                . '<span class="icon-remove" aria-hidden="true"></span>' . Text::_('JCLEAR')
            . '</button>';
  }

        if ($allowSelect || $allowNew || $allowEdit || $allowClear) {
            $html .= '</span>';
  }

        // Select dataset modal
        if ($allowSelect) {
            $html .= HTMLHelper::_(
                'bootstrap.renderModal',
                'ModalSelect' . $modalId,
                [
                    'title'       => $modalTitle,
                    'url'         => $urlSelect,
                    'height'      => '400px',
                    'width'       => '800px',
                    'bodyHeight'  => 70,
                    'modalWidth'  => 80,
                    'datasetter'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
                        . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
                ]
            );
        }

        // Note: class='required' for client side validation.
        $class = $this->required ? ' class="required modal-value"' : '';

        $html .= '<input type="hidden" id="'
            . $this->id . '_id"'
            . $class . ' data-required="' . (int) $this->required
            . '" name="jform[request][id]'
            . '" data-text="'
            . htmlspecialchars(Text::_('COM_VMMAPICON_SELECT_A_DATASET', true), ENT_COMPAT, 'UTF-8')
            . '" value="' . $value . '">';

        return $html;
    }

    /**
     * Method to get the field label markup.
     *
     * @return  string  The field label markup.
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getLabel()
    {
        return str_replace($this->id, $this->id . '_name', parent::getLabel());
    }
}
 