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
namespace Villaester\Component\Vmmapicon\Administrator\Service\HTML;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Villaester\Component\Vmmapicon\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

/**
 * Content Component HTML Helper
 *
 * @since  1.0.0
 */
class Icon
{
	/**
	 * The application
	 *
	 * @var    CMSApplication
	 *
	 * @since  1.0.0
	 */
	private $application;

	/**
	 * Service constructor
	 *
	 * @param   CMSApplication  $application  The application
	 *
	 * @since   1.0.0
	 */
	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	/**
	 * Method to generate a link to the create item page for the given category
	 *
	 * @param   object    $category  The category information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item link
	 *
	 * @since  1.0.0
	 */
	public static function create($category, $params, $attribs = array())
	{
		$uri = Uri::getInstance();

		$url = 'index.php?option=com_vmmapicon&task=api.add&return=' . base64_encode($uri) . '&id=0&catid=' . $category->id;

		$text = LayoutHelper::render('joomla.content.icons.create', array('params' => $params, 'legacy' => false));

		// Add the button classes to the attribs array
		if (isset($attribs['class']))
		{
			$attribs['class'] .= ' btn btn-primary';
		}
		else
		{
			$attribs['class'] = 'btn btn-primary';
		}

		$button = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . HTMLHelper::_('tooltipText', 'COM_VMMAPICON_CREATE_REALESTATE') . '">' . $button . '</span>';

		return $output;
	}

	/**
	 * Display an edit icon for the api.
	 *
	 * This icon will not display in a popup window, nor if the api is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $api  The api information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the api edit icon.
	 *
	 * @since   1.0.0
	 */
	public static function edit($api, $params, $attribs = array(), $legacy = false)
	{
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}

		// Ignore if the state is negative (trashed).
		if ($api->published < 0)
		{
			return '';
		}

		// Set the link class
		$attribs['class'] = 'dropdown-item';

		// Show checked_out icon if the api is checked out by a different user
		if (property_exists($api, 'checked_out')
			&& property_exists($api, 'checked_out_time')
			&& $api->checked_out > 0
			&& $api->checked_out != $user->get('id'))
		{
			$checkoutUser = Factory::getApplication()->getIdentity($api->checked_out);
			$date         = HTMLHelper::_('date', $api->checked_out_time);
			$tooltip      = Text::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . Text::sprintf('COM_VMMAPICON_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;

			$text = LayoutHelper::render('joomla.content.icons.edit_lock', array('tooltip' => $tooltip, 'legacy' => $legacy));

			$output = HTMLHelper::_('link', '#', $text, $attribs);

			return $output;
		}

		if (!isset($api->slug))
		{
			$api->slug = "";
		}

		$apiUrl = RouteHelper::getApiRoute($api->slug, $api->catid, $api->language);
		$url        = $apiUrl . '&task=api.edit&id=' . $api->id . '&return=' . base64_encode($uri);

		if ($api->published == 0)
		{
			$overlib = Text::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = Text::_('JPUBLISHED');
		}

		if (!isset($api->created))
		{
			$date = HTMLHelper::_('date', 'now');
		}
		else
		{
			$date = HTMLHelper::_('date', $api->created);
		}

		if (!isset($created_by_alias) && !isset($api->created_by))
		{
			$author = '';
		}
		else
		{
			$author = $api->created_by_alias ?: Factory::getApplication()->getIdentity($api->created_by)->name;
		}

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= Text::sprintf('COM_VMMAPICON_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		$icon = $api->published ? 'edit' : 'eye-slash';

		if (strtotime($api->publish_up) > strtotime(Factory::getDate())
			|| ((strtotime($api->publish_down) < strtotime(Factory::getDate())) && $api->publish_down != Factory::getContainer()->get('DatabaseDriver')->getNullDate()))
		{
			$icon = 'eye-slash';
		}

		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_VMMAPICON_EDIT_REALESTATE'), $overlib, 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');

		$attribs['title'] = Text::_('COM_VMMAPICON_EDIT_REALESTATE');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}
}
