<?php
/**
 *
 *
 * \ \    / /  \/  |  \/  |
 *  \ \  / /| \  / | \  / |
 *   \ \/ / | |\/| | |\/| |
 *    \  /  | |  | | |  | |
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH * * @package Joomla.Component
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Administrator\Field\Apis;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;

class ApisField extends ListField
{
	protected $type = 'Apis';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 */
	protected function getInput()
	{
		$data = $this->collectLayoutData();

		$data['options'] = (array) $this->getOptions();

		return $this->getRenderer($this->layout)->render($data);
	}
	
	protected function getOptions(): array
	{

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('params')
			->from($db->qn('#__extensions'))
			->where($db->qn('name') . ' = ' . $db->q('com_vmmapicon'));
		$db->setQuery($query);

		$paramsJson = $db->loadResult();
		$params = json_decode($paramsJson, true);

		$options = [];

		if (isset($params['apis']) && is_array($params['apis'])) {
			foreach ($params['apis'] as $key => $api) {
				$title = $api['api-title'] ?? $key;
				$options[] = HTMLHelper::_('select.option', $key, $title);
			}
		}

		return array_merge(parent::getOptions(), $options);
	}
}