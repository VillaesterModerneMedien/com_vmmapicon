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
 */

namespace Joomla\Plugin\System\Ytvmmapicon\Type;

class ApiConfigType
{
    public static function config(): array
    {
        return [
            'fields' => [
                // Grunddaten
                'id' => [ 'type' => 'String', 'metadata' => ['label' => 'ID'] ],
                'title' => [ 'type' => 'String', 'metadata' => ['label' => 'Titel'] ],
				'api_url' => [ 'type' => 'String', 'metadata' => ['label' => 'Api URL'] ],
	            'api_method' => [ 'type' => 'String', 'metadata' => ['label' => 'Api Method'] ],
	            'api_type' => [ 'type' => 'String', 'metadata' => ['label' => 'Api Type'] ],
            ],
            'metadata' => [ 'type' => true, 'label' => 'Api Config' ],
        ];
    }

    public static function resolve($item, $args, $context, $info)
    {
        $field = $info->fieldName;
        return $item->$field ?? null;
    }
}
