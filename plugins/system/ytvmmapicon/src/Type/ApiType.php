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

class ApiType
{
    public static function config(): array
    {
        return [
            'fields' => [
                // Grunddaten
                'id' => [ 'type' => 'String', 'metadata' => ['label' => 'ID'] ],
                'type' => [ 'type' => 'String', 'metadata' => ['label' => 'Type'] ],
                'title' => [ 'type' => 'String', 'metadata' => ['label' => 'Titel'] ],
                'alias' => [ 'type' => 'String', 'metadata' => ['label' => 'Alias'] ],
                'state' => [ 'type' => 'Int', 'metadata' => ['label' => 'State'] ],
                'access' => [ 'type' => 'Int', 'metadata' => ['label' => 'Access'] ],
                'created' => [ 'type' => 'String', 'metadata' => ['label' => 'Created', 'filters' => ['date']] ],
                'created_by' => [ 'type' => 'String', 'metadata' => ['label' => 'Created By'] ],
                'modified' => [ 'type' => 'String', 'metadata' => ['label' => 'Modified', 'filters' => ['date']] ],
                'featured' => [ 'type' => 'Int', 'metadata' => ['label' => 'Featured'] ],
                'language' => [ 'type' => 'String', 'metadata' => ['label' => 'Language'] ],
                'hits' => [ 'type' => 'Int', 'metadata' => ['label' => 'Hits'] ],
                'publish_up' => [ 'type' => 'String', 'metadata' => ['label' => 'Publish Up', 'filters' => ['date']] ],
                'publish_down' => [ 'type' => 'String', 'metadata' => ['label' => 'Publish Down', 'filters' => ['date']] ],
                'note' => [ 'type' => 'String', 'metadata' => ['label' => 'Note'] ],

                // Bilder
                'image_intro' => [ 'type' => 'String', 'metadata' => ['label' => 'Image Intro'] ],
                'image_intro_alt' => [ 'type' => 'String', 'metadata' => ['label' => 'Image Intro Alt'] ],
                'image_fulltext' => [ 'type' => 'String', 'metadata' => ['label' => 'Image Fulltext'] ],
                'image_fulltext_alt' => [ 'type' => 'String', 'metadata' => ['label' => 'Image Fulltext Alt'] ],

                // Metadaten
                'metakey' => [ 'type' => 'String', 'metadata' => ['label' => 'Meta Keywords'] ],
                'metadesc' => [ 'type' => 'String', 'metadata' => ['label' => 'Meta Description'] ],
                'metadata_robots' => [ 'type' => 'String', 'metadata' => ['label' => 'Robots'] ],
                'metadata_author' => [ 'type' => 'String', 'metadata' => ['label' => 'Author'] ],
                'metadata_rights' => [ 'type' => 'String', 'metadata' => ['label' => 'Rights'] ],

                // Weitere Felder
                'version' => [ 'type' => 'Int', 'metadata' => ['label' => 'Version'] ],
                'featured_up' => [ 'type' => 'String', 'metadata' => ['label' => 'Featured Up', 'filters' => ['date']] ],
                'featured_down' => [ 'type' => 'String', 'metadata' => ['label' => 'Featured Down', 'filters' => ['date']] ],
                'typeAlias' => [ 'type' => 'String', 'metadata' => ['label' => 'Type Alias'] ],
                'text' => [ 'type' => 'String', 'metadata' => ['label' => 'Text', 'filters' => ['limit']] ],
                'testfeld' => [ 'type' => 'String', 'metadata' => ['label' => 'Testfeld'] ],
                'auszug' => [ 'type' => 'String', 'metadata' => ['label' => 'Auszug', 'filters' => ['limit']] ],
                'bilder' => [
                    'type' => ['listOf' => 'ApiImage'],
                    'metadata' => [ 'label' => 'Bilder' ],
                ],

                // Beziehungen
                'category_id' => [ 'type' => 'String', 'metadata' => ['label' => 'Category ID'] ],
                'category_name' => [ 'type' => 'String', 'metadata' => ['label' => 'Category Name'] ],
                'category_alias' => [ 'type' => 'String', 'metadata' => ['label' => 'Category Alias'] ],
                'author_id' => [ 'type' => 'String', 'metadata' => ['label' => 'Author ID'] ],
                'tags_ids' => [ 'type' => ['listOf' => 'String'], 'metadata' => ['label' => 'Tag IDs'] ],

                // Links
                'self_link' => [ 'type' => 'String', 'metadata' => ['label' => 'Self Link'] ],

                // Rohdaten für Debug
                'raw' => [ 'type' => 'String', 'metadata' => ['label' => 'Raw API Data'] ],
            ],
            'metadata' => [ 'type' => true, 'label' => 'Api Response' ],
        ];
    }

    public static function resolve($item, $args, $context, $info)
    {
        $field = $info->fieldName;
        return $item->$field ?? null;
    }
}
