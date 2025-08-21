<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Villaester\Component\Vmmapicon\Site\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Model;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Table\TableInterface;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\UCM\UCMType;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Prototype admin model.
 *
 * @since  1.6
 */
class ApiModel extends AdminModel
{

    /**
     * Constructor.
     *
     * @param   array                  $config       An array of configuration options (name, state, dbo, table_path, ignore_request).
     * @param   ?MVCFactoryInterface   $factory      The factory.
     * @param   ?FormFactoryInterface  $formFactory  The form factory.
     *
     * @since   1.6
     * @throws  \Exception
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, FormFactoryInterface $formFactory = null)
    {
        parent::__construct($config, $factory, $formFactory);
    }


    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  \stdClass|false  Object on success, false on failure.
     *
     * @since   1.6
     */
    public function getItem($pk = null)
    {
        $pk    = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
        $table = $this->getTable();

        if ($pk > 0) {
            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if ($return === false) {
                // If there was no underlying error, then the false means there simply was not a row in the db for this $pk.
                if (!$table->getError()) {
                    $this->setError(Text::_('JLIB_APPLICATION_ERROR_NOT_EXIST'));
                } else {
                    $this->setError($table->getError());
                }

                return false;
            }
        }

        // Convert to the CMSObject before adding other data.
        $properties = $table->getProperties(1);
        $item       = ArrayHelper::toObject($properties, CMSObject::class);

        if (property_exists($item, 'params')) {
            $registry     = new Registry($item->params);
            $item->params = $registry->toArray();
        }

        return $item;
    }

}
