<?php
/**
 *
 *
 * \ \    / /  \/  |  \/  |
 *  \ \  / /| \  / | \  / |
 *   \ \/ / | |\/| | |\/| |
 *    \  /  | |  | | |  | |
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH * * @package Joomla.Component
 * @subpackage  com_vmmapico
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */
namespace Villaester\Component\Vmmapicon\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Villaester\Component\Vmmapicon\Administrator\Model\ApiModel;

/**
     * Vmmapicon component helper.
     *
     * @since  1.0.0
     */
    class VmmapiconHelper extends ContentHelper
    {
		public static function getApiConfig()
		{

			$app = Factory::getApplication();
			$input = $app->input;
			$apiId = $input->get('id');
			$mvcFactory = $app->bootComponent('com_vmmapicon')->getMVCFactory();

			/** @var ApiModel $apiModel **/
			$apiModel = $mvcFactory->createModel('Api', 'Administrator', ['ignore_request' => true]);
			$result = $apiModel->getItem($apiId);

			return $result;
		}
    }
