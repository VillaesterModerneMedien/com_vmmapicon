<?php
/**
 *
 *
 * \ \    / /  \/  |  \/  |
 *  \ \  / /| \  / | \  / |
 *   \ \/ / | |\/| | |\/| |
 *    \  /  | |  | | |  | |
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH
 *
 * @package     Joomla.Component
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Site\Model;

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Villaester\Component\Vmmapicon\Site\Helper\VmmapiconHelper;

\defined('_JEXEC') or die;

class ApisModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array                 $config    An optional associative array of configuration settings.
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @see     \JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = [
				'id', 'a.id',
				'title', 'a.title',
			];
		}

		parent::__construct($config);
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItems()
	{
		$apiItems = VmmapiconHelper::getApiData();
		return $apiItems;
	}

	/**
	 * Get the total number of items.
	 *
	 * @return integer The total number of items.
	 * @since  1.0.0
	 */
	public function getTotal()
	{
		return 0;
	}
}
