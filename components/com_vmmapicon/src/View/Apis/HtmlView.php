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

namespace Villaester\Component\Vmmapicon\Site\View\Apis;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\ListView;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * HTML Article View class for the Content component
 *
 * @since  1.5
 */
class HtmlView extends ListView
{
	/**
	 * The article object
	 *
	 * @var  \stdClass
	 */
	protected $items;

	/**
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 *
	 * @since  4.0.0
	 */
	protected $params = null;

	/**
	 * Should the print button be displayed or not?
	 *
	 * @var   boolean
	 */
	protected $print = false;

	/**
	 * The model state
	 *
	 * @var   \Joomla\CMS\Object\CMSObject
	 */
	protected $state;

	/**
	 * The user object
	 *
	 * @var   \Joomla\CMS\User\User|null
	 */
	protected $user = null;

	/**
	 * The page class suffix
	 *
	 * @var    string
	 *
	 * @since  4.0.0
	 */
	protected $pageclass_sfx = '';

	/**
	 * The flag to mark if the active menu item is linked to the being displayed article
	 *
	 * @var boolean
	 */
	protected $menuItemMatchArticle = false;


	/**
	 * Prepare view data
	 *
	 * @return  void
	 */
	protected function initializeView()
	{
		$this->option = 'com_vmmapicon';
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
	}



}
