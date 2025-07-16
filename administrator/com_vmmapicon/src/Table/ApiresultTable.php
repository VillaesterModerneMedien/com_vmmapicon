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

namespace Villaester\Component\Vmmapicon\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * ApiResult table class.
 * This is a virtual table for API results - no actual database table exists.
 *
 * @since  1.0.0
 */
class ApiresultTable extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since   1.0.0
     */
    public function __construct(DatabaseDriver $db)
    {
        // This is a virtual table - no actual database table
        $this->_tbl = '#__vmmapicon_apiresults_virtual';
        $this->_tbl_key = 'id';

        parent::__construct($this->_tbl, $this->_tbl_key, $db);
    }

    /**
     * Overridden load method - loads from API instead of database
     *
     * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.
     * @param   boolean  $reset  True to reset the default values before loading the new row.
     *
     * @return  boolean  True if successful. False if row not found.
     *
     * @since   1.0.0
     */
    public function load($keys = null, $reset = true)
    {
        // This is a virtual table - implement custom loading logic if needed
        return false;
    }

    /**
     * Overridden store method - API results are read-only
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     *
     * @since   1.0.0
     */
    public function store($updateNulls = true)
    {
        // API results are read-only
        return false;
    }

    /**
     * Overridden delete method - API results cannot be deleted
     *
     * @param   mixed  $pk  An optional primary key value to delete.
     *
     * @return  boolean  True on success.
     *
     * @since   1.0.0
     */
    public function delete($pk = null)
    {
        // API results cannot be deleted
        return false;
    }

    /**
     * Overridden check method
     *
     * @return  boolean  True on success.
     *
     * @since   1.0.0
     */
    public function check()
    {
        // No validation needed for virtual table
        return true;
    }
}
