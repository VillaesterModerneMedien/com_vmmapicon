<?php

namespace Joomla\Plugin\System\Ytvmmapicon;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Villaester\Component\Vmmapicon\Administrator\Helper\ApiHelper;
use function YOOtheme\app;


/**
 * Custom Type Provider
 */
class ApiTypeProvider
{
    public static function get($id)
    {
        // Rückwärtskompatibilität: liefert das erste Element
        return self::getSingle($id, 0, null);
    }


public static function getSingle($id, int $index = 0, ?string $articleId = null)
    {
		$app = Factory::getApplication();
		$input = $app->input;
        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $api = $model->getItem($id);
        if (!$api) {
            return null;
        }
		$articleId = $input->get('articleId');

		$apiSingleModel = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('ApiSingle', 'Site');
        $apiResponse = $apiSingleModel->getItem($articleId);

	    $baseUrl = $api->api_url;
	    $out = [];

		$out[] = self::flattenRecord($apiResponse, $baseUrl);

	    return $out;

    }

    public static function getList($id, ?int $limit = null, int $offset = 0, String $template = null): array
    {
        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $api = $model->getItem($id);
        if (!$api) {
            return [];
        }

		$apiBlogModel = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('ApiBlog', 'Site');
	    $items = $apiBlogModel->getItems($api->id, $limit, $offset, $template);

		$baseUrl = $api->api_url;
	    $out = [];

		foreach ($items as $item) {
			$out[] = self::flattenRecord($item, $baseUrl);
		}

        return $out;
    }

	private static function originFromUrl(?string $url): string
    {
        if (!$url) { return ''; }
        $p = parse_url($url);
        if (!$p || empty($p['host'])) { return ''; }
        $scheme = $p['scheme'] ?? 'https';
        $host = $p['host'];
        $port = isset($p['port']) ? ':' . $p['port'] : '';
        return $scheme . '://' . $host . $port;
    }

    private static function isAbsoluteUrl(string $val): bool
    {
        return (bool) preg_match('#^([a-z]+:)?//#i', $val) || str_starts_with($val, 'data:');
    }

    private static function absoluteUrl(?string $path, string $origin): string
    {
        $val = (string) ($path ?? '');
        if ($val === '') { return ''; }
        if (self::isAbsoluteUrl($val)) { return $val; }
        $val = ltrim($val, '/');
        return $origin !== '' ? ($origin . '/' . $val) : '/' . $val;
    }

    private static function normalizeBilder($bilderJson, string $origin): array
    {
        // $bilderJson kann bereits Array oder JSON-String sein
        if (is_string($bilderJson) && $bilderJson !== '') {
            $arr = json_decode($bilderJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $arr = [];
            }
        } elseif (is_array($bilderJson)) {
            $arr = $bilderJson;
        } else {
            $arr = [];
        }
        $result = [];
        foreach ($arr as $row) {
            if (!is_array($row)) { continue; }
            $file = $row['field2']['imagefile'] ?? '';
            $alt  = $row['field2']['alt_text'] ?? '';
            $cap  = $row['field5'] ?? '';
            if ($file === '') { continue; }
            $path = ltrim((string) $file, '/');
            $src  = ($origin !== '') ? ($origin . '/' . $path) : $path;
            $result[] = (object) [
                'src' => $src,
                'alt' => (string) $alt,
                'caption' => (string) $cap,
            ];
        }
        return $result;
    }

    private static function flattenRecord(array $rec, ?string $baseUrl = null)
    {
		$view = Factory::getApplication()->input->get('view');
		if($view === 'apisingle') {
			$attributes = $rec;
		} else {
			$attributes = $rec['attributes'] ?? [];
			$relationships = $rec['relationships'] ?? [];
		}

        $images = $attributes['images'] ?? [];
        $metadata = $attributes['metadata'] ?? [];


        $category = $relationships['category']['data']['id'] ?? null;
        $author = $relationships['created_by']['data']['id'] ?? null;
        $tags = $relationships['tags']['data'] ?? [];
        $tagIds = [];
        if (is_array($tags)) {
            foreach ($tags as $t) {
                if (isset($t['id'])) { $tagIds[] = (string) $t['id']; }
            }
        }

        $origin = self::originFromUrl($baseUrl);
        $bilderList = self::normalizeBilder($attributes['bilder'] ?? '', $origin);

        $obj = (object) [
            // Top-level
            'id' => (string) ($rec['id'] ?? ''),
            'type' => (string) ($rec['type'] ?? ''),

            // Attributes
            'title' => (string) ($attributes['title'] ?? ''),
            'alias' => (string) ($attributes['alias'] ?? ''),
            'state' => isset($attributes['state']) ? (int) $attributes['state'] : null,
            'access' => isset($attributes['access']) ? (int) $attributes['access'] : null,
            'created' => (string) ($attributes['created'] ?? ''),
            'created_by' => isset($attributes['created_by']) ? (string) $attributes['created_by'] : null,
            'modified' => (string) ($attributes['modified'] ?? ''),
            'featured' => isset($attributes['featured']) ? (int) $attributes['featured'] : null,
            'language' => (string) ($attributes['language'] ?? ''),
            'hits' => isset($attributes['hits']) ? (int) $attributes['hits'] : null,
            'publish_up' => (string) ($attributes['publish_up'] ?? ''),
            'publish_down' => (string) ($attributes['publish_down'] ?? ''),
            'note' => (string) ($attributes['note'] ?? ''),

            // Images (core) – jetzt absolut
            'image_intro' => self::absoluteUrl($images['image_intro'] ?? '', $origin),
            'image_intro_alt' => (string) ($images['image_intro_alt'] ?? ''),
            'image_fulltext' => self::absoluteUrl($images['image_fulltext'] ?? '', $origin),
            'image_fulltext_alt' => (string) ($images['image_fulltext_alt'] ?? ''),

            // Meta
            'metakey' => (string) ($attributes['metakey'] ?? ''),
            'metadesc' => (string) ($attributes['metadesc'] ?? ''),
            'metadata_robots' => (string) ($metadata['robots'] ?? ''),
            'metadata_author' => (string) ($metadata['author'] ?? ''),
            'metadata_rights' => (string) ($metadata['rights'] ?? ''),

            // More
            'version' => isset($attributes['version']) ? (int) $attributes['version'] : null,
            'featured_up' => (string) ($attributes['featured_up'] ?? ''),
            'featured_down' => (string) ($attributes['featured_down'] ?? ''),
            'typeAlias' => (string) ($attributes['typeAlias'] ?? ''),
            'text' => (string) ($attributes['text'] ?? ''),
            'testfeld' => (string) ($attributes['testfeld'] ?? ''),
            'bilder' => $bilderList,
            'article_field' => isset($attributes['article-field']) ? (string) $attributes['article-field'] : '',
			'self_link' => isset($attributes['self_link']) ? (string) $attributes['self_link'] : '',
	        // Relationships
            'category_id' => $category ? (string) $category : null,
            'author_id' => $author ? (string) $author : null,
            'tags_ids' => $tagIds,

            // Raw
            'raw' => json_encode($rec, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];
        return $obj;
    }
}
