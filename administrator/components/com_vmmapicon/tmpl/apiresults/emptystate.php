<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;



use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

$displayData = [
	'textPrefix' => 'COM_VMMAPICON',
	'formURL' => 'index.php?option=com_vmmapicon',
	'icon' => 'icon-file',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_vmmapicon') || count($user->getAuthorisedCategories('com_vmmapicon', 'core.create')) > 0)
{
	$displayData['createURL'] = 'index.php?option=com_vmmapicon&task=api.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
