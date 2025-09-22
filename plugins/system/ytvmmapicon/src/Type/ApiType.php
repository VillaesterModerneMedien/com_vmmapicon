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
		// Standardmäßig erstes veröffentlichtes API verwenden; bei Schema-Refresh nach ID-Änderung wird neu aufgebaut
		$apiOptions = ApiQueryType::getApiOptions();
		$apiId = $apiOptions[0]['value'] ?? null;

		$fields = [];

		if ($apiId !== null) {
			// Mapping nur aus der Komponente laden (kein APICall im Schemaaufbau)
			$model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
			$mapping = $model->getMapping($apiId) ?? [];

			foreach ($mapping as $field) {
				$typeDecl = self::toGraphQLTypeDecl($field['field_type'] ?? 'String');
				$fields[$field['yootheme_name']] = [
					'type' => $typeDecl,
					'metadata' => [
						'label' => $field['field_label'] ?? $field['yootheme_name'],
					],
					'extensions' => [
						'call' => __CLASS__ . '::resolve',
					],
				];
			}
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

			foreach ($item->mapping_fields as $mappingItem) {
				if (($mappingItem['yootheme_name'] ?? null) === $fieldName) {
					$value = self::extractValueFromPath($item->api_data, $mappingItem['json_path'] ?? '');
					$type  = $mappingItem['field_type'] ?? 'String';

					// listOf(String): immer ein Array von Strings zurückgeben
					if (self::isListOf($type)) {
						if ($value === null) {
							return [];
						}
						if (!is_array($value)) {
							// Objekt oder Skalar in ein 1-Element-Array serialisieren
							if (is_object($value) || is_array($value)) {
								return [json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)];
							}
							return [(string) $value];
						}
						// Array-Elemente in Strings umwandeln
						return array_map(function($v){
							if (is_scalar($v) || $v === null) {
								return $v === null ? '' : (string) $v;
							}
							return json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
						}, $value);
					}

					// Skalar: nie ein Array/Objekt zurückgeben
					if (is_array($value) || is_object($value)) {
						return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
					}
					return $value;
				}
			}
		}

		return null;
	}

	private static function extractValueFromPath($data, $path)
	{
		$pathSegments = $path !== '' ? explode('->', $path) : [];
		$current = $data;

		for ($i = 0, $n = count($pathSegments); $i < $n; $i++) {
			$segment = $pathSegments[$i];

			if (is_array($current)) {
				if (array_key_exists($segment, $current)) {
					$current = $current[$segment];
					continue;
				}
				// Wenn es eine Liste ist und der nächste Schlüssel nicht numerisch ist, nimm Index 0 implizit
				$keys = array_keys($current);
				$sequential = $keys === range(0, count($current) - 1);
				if ($sequential && isset($current[0])) {
					// Falls der aktuelle Segmentname eigentlich ein Index ist, nutze ihn
					if (ctype_digit((string) $segment) && isset($current[(int) $segment])) {
						$current = $current[(int) $segment];
						continue;
					}
					// Andernfalls implizit erstes Element und denselben Segmentnamen erneut versuchen
					$current = $current[0];
					$i--; // denselben Segmentnamen erneut prüfen
					continue;
				}
				return null;
			}
			elseif (is_object($current)) {
				if (isset($current->$segment)) {
					$current = $current->$segment;
					continue;
				}
				return null;
			}
			else {
				return null;
			}
		}

		return $current;
	}

	private static function toGraphQLTypeDecl(string $type)
	{
		$scalar = ['String','Int','Float','Boolean'];
		if (in_array($type, $scalar, true)) {
			return $type;
		}
		if (self::isListOf($type)) {
			$inner = trim(substr($type, strlen('listOf('), -1)) ?: 'String';
			if (!in_array($inner, $scalar, true)) {
				$inner = 'String';
			}
			return ['listOf' => $inner];
		}
		return 'String';
	}

	private static function isListOf(string $type): bool
	{
		return str_starts_with($type, 'listOf(') && str_ends_with($type, ')');
	}
}
