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
 * @package Joomla.Component
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

namespace Villaester\Component\Vmmapicon\Site\Service;


use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\Router\RouterBase;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\ParameterType;

/**
 * Routing class from com_banners
 *
 * @since  3.3
 */

class Router extends RouterView
{

	public function __construct(SiteApplication $app, AbstractMenu $menu)
	{
		$views = ['apiblog', 'apisingle'];

		foreach($views as $view)
		{
			$route = new RouterViewConfiguration($view);
			$this->registerView($route);
		}

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}


	/**
	 * Build the route for the com_banners component
	 *
	 * @param   array  $query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();

		if (isset($query['view']) && ($query['view'] === 'apiblog'))
		{
			$segments[] = ' ';
			unset($query['view']);
		}
		if (isset($query['view']) && ($query['view'] === 'apisingle'))
		{
			//https://vmmapicon.joomla.local:8482/apitest/der-rothschoenberger-stolln
			$segments[] = ' ';
			$segments[] = $query['alias'];

			unset($query['articleId']);
			unset($query['view']);
			unset($query['alias']);
			unset($query['id']);
			unset($query['Itemid']);

		}

		if (isset($query['id']))
		{
			unset($query['id']);
		}

		return $segments;
	}

	/**
	 * Parse method for URLs
	 * This method is meant to transform the human readable URL back into
	 * query parameters. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$vars = array();

		if(count($segments) === 1){
			$vars['view'] = 'apisingle';
		}
		$segments = [];

		return $vars;
	}

}

