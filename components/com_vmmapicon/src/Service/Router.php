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
use PHPSQLParser\Test\Parser\issue11Test;

/**
 * Routing class from com_banners
 *
 * @since  3.3
 */

class Router extends RouterView
{

	public function __construct(SiteApplication $app, AbstractMenu $menu)
	{
		$views = ['apiblog', 'apisingle', 'article'];

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
		$segments = [];

		// Liste: vom Menü abgedeckt, keine Segmente erzeugen
		if (isset($query['view']) && $query['view'] === 'apiblog') {
			unset($query['view'], $query['id']);
			return $segments;
		}

		// Einzelansicht (von 'article' oder direkt 'apisingle' kommend)
		if (isset($query['view']) && ($query['view'] === 'apisingle')) {

			// Segmente: /{category}/{alias}
			if (!empty($query['category'])) {
				$segments[] = $query['category'];
			}
			if (!empty($query['alias'])) {
				$segments[] = $query['alias'];
			}

			// Überflüssige Parameter entfernen (SEF)
			unset($query['Itemid'],$query['view'], $query['alias'], $query['category'], $query['articleId'], $query['id']);
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
		/*$menu = Factory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemParams = $active->query;
		$apiId = $itemParams['id'];*/

		$model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Apisingle', 'Site');

		$vars = array();

			$articleId = $model->getMapping($segments[1], $segments[0]);
			$vars['view'] = 'apisingle';
			$vars['articleId'] = $articleId;

		$segments = [];

		return $vars;
	}

}
