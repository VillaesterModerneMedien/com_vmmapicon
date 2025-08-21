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

//
namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use Joomla\CMS\Factory;
use Joomla\Plugin\System\Ytvmmapicon\ApiTypeProvider;
use Joomla\Plugin\System\Ytvmmapicon\Type\ApiQueryType;


class ApiType
{
	public static function config()
	{
		$selectedApi = ApiQueryType::getApiOptions();
		$apiId = $selectedApi[0]['value'];
		$model  = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
		$mapping = $model->getMapping($apiId);
		$data = '';
		$fields = [];
		error_log("Terrorlog ");
		file_put_contents('/tmp/debug.log', "Resolve called: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

		foreach ($mapping as $key => $field) {
			$fields[$field['yootheme_name']] = [
				'type' => $field['field_type'],
				'metadata' => [
					'label' => $field['field_label'],
				],
				'extensions' => [
					'call' => __CLASS__ . '::resolve',
				],
			];
		}

		return [
			'fields' => $fields,

			'metadata' => [
				'type' => true,
				'label' => 'Api Response',
			],
		];
	}

	public static function resolve($item, $args, $context, $info)
	{

		if (isset($item->api_data) && isset($item->mapping_fields)) {
			$fieldName = $info->fieldName;

			// Entsprechendes Mapping für das aktuelle Feld finden
			foreach ($item->mapping_fields as $mappingItem) {
				if ($mappingItem['yootheme_name'] === $fieldName) {
					return self::extractValueFromPath($item->api_data, $mappingItem['json_path']);
				}
			}
		}

		return null;
	}

	private static function extractValueFromPath($data, $path)
	{
		$pathSegments = explode('->', $path);
		$current = $data;

		foreach ($pathSegments as $segment) {
			if (is_array($current) && isset($current[$segment])) {
				$current = $current[$segment];
			} elseif (is_object($current) && isset($current->$segment)) {
				$current = $current->$segment;
			} else {
				return null;
			}
		}

		return $current;
	}
}
