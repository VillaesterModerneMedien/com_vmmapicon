<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Villaester\Component\Vmmapicon\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * Api Table class.
 *
 * @since  1.0.0
 */
class ApiTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
    use TaggableTableTrait;

    /**
     * Indicates that columns fully support the NULL value in the database
     *
     * @var    boolean
     * @since  1.0.0
     */
    protected $_supportNullValue = true;

    /**
     * Ensure the params and metadata in json encoded in the bind method
     *
     * @var    array
     * @since  1.0.0
     */
    //protected $_jsonEncode = array('params', 'metadata');

    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since   1.0.0

     */
    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_vmmapicon.api';

        parent::__construct('#__vmmapicon_apis', 'id', $db);
    }

    /**
     * Get the type alias for the history table
     *
     * @return  string  The alias as described above
     *
     * @since   1.0.0
     */
    public function getTypeAlias()
    {
        return $this->typeAlias;
    }

}
