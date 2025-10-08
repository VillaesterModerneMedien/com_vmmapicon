<?php
namespace Villaester\Component\Vmmapicon\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;

class ApiHelper
{
    protected static function formatParams($allParams)
    {
        if ($allParams === null) {
            return ['head' => [], 'body' => [], 'url' => ''];
        }
        $apiParams = json_decode($allParams, true);
        if (!is_array($apiParams)) {
            return ['head' => [], 'body' => [], 'url' => ''];
        }

        $params = ['head' => [], 'body' => [], 'url' => []];

        foreach ($apiParams as $param) {
            if (!is_array($param)) continue;
            $key = $param['key'] ?? '';
            $val = $param['value'] ?? '';
            $pos = $param['position'] ?? '';
            switch ($pos) {
                case 'head':
                    $params['head'][] = trim($key) . ': ' . $val;
                    break;
                case 'url':
                    $params['url'][$key] = $val;
                    break;
                case 'body':
                    $params['body'][$key] = $val;
                    break;
                default:
                    break;
            }
        }
        $params['url'] = !empty($params['url']) ? http_build_query($params['url']) : '';
        return $params;
    }

	public static function getCategoryNames(String $apiUrl, array $params): array{

		$url = str_replace('/articles', '/categories', $apiUrl);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'GET',
			CURLOPT_HTTPHEADER     => $params,
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$responseArray = json_decode($response, true);
		$categories = [];
		foreach ($responseArray['data'] as $category){
			$categories [$category['id']] = [$category['attributes']['title'], $category['attributes']['alias']];
 ;
		}
		return  (array) $categories;

	}
    public static function getApiResult(object $apiConfig, int $start = 0, int $limit = 20): string
    {
		$app = Factory::getApplication();
		$input = $app->getInput();
	    $Itemid = $input->get('Itemid');
		$view = $input->get('view');
		$url = $apiConfig->api_url ?? '';
		$method = isset($apiConfig->api_method) ? strtoupper($apiConfig->api_method) : 'GET';
        $params = self::formatParams($apiConfig->api_params ?? null);

	    if (!$url) {
            return '';
        }

		$categories = self::getCategoryNames($url, $params['head']);

        if (!empty($params['url'])) {
            $url .= (str_contains($url, '?') ? '&' : '?') . $params['url'];
        }

		if ($view === "apisingle"){
			$url = $apiConfig->api_url . "/" . $input->get('articleId');
		} else if($view === "apiblog" || $view === "article") {
			$viewUrl = 'apisingle';
			$url .= "&page[offset]=" . $start . "&page[limit]=" . $limit;
		} else {
			$viewUrl = $view;
		}

	    $curl = curl_init();
        $opts = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $params['head'] ?: [],
        ];
        if ($method !== 'GET' && !empty($params['body'])) {
            $opts[CURLOPT_POSTFIELDS] = $params['body'];
        }
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);

	    $responseArray = json_decode($response, true);

		$responseArray['view'] = $view;
	    $idMapping = [];
		$blogId = self::_getBlogMenu($apiConfig->id);

		if($view === 'apisingle'){
			$categoryName = $categories[$responseArray['data']['relationships']['category']['data']['id']][0];
			$categoryAlias = $categories[$responseArray['data']['relationships']['category']['data']['id']][1];
			$responseArray['data']['attributes']['categoryname'] = $categoryName;
			$responseArray['data']['attributes']['categoryalias'] = $categoryAlias;


		} else {
			foreach ($responseArray['data'] as $key => $value){
				$categoryName = $categories[$responseArray['data'][$key]['relationships']['category']['data']['id']][0];
				$categoryAlias = $categories[$responseArray['data'][$key]['relationships']['category']['data']['id']][1];
				$idMapping[$value['id']]['categoryAlias'] = $categoryAlias;
				$responseArray['data'][$key]['attributes']['categoryname'] = $categoryName;
				$responseArray['data'][$key]['attributes']['categoryalias'] = $categoryAlias;
				$idMapping[$value['id']]['articleAlias'] = $value['attributes']['alias'];
				$responseArray['data'][$key]['attributes']['self_link'] = "index.php?option=com_vmmapicon&view=apisingle&id=" . $apiConfig->id . "&articleId=" . $value['id'] . '&alias=' . $value['attributes']['alias'] . '&Itemid=' . $blogId . '&category=' . $categoryAlias;
			}
		}

	    self::_setMappings($idMapping, $apiConfig->id );

		$response = json_encode($responseArray);
	    curl_close($curl);
        return (string) $response;
    }

	private static function _getBlogMenu($id = null){
		$menu = Factory::getApplication()->getMenu();

		foreach ($menu->getMenu() as $item) {
			if (($item->query['view'] === 'apiblog') && ($item->query['id'] == $id)) {
				return (int) $item->id;
			}
		}
		return null;
	}
	private static function _setMappings($idMapping, $apiId){
		$db = Factory::getContainer()->get(DatabaseInterface::class);

		if (empty($idMapping) || !$apiId) {
			return;
		}

		try {
			$db->transactionStart();

			$query = $db->getQuery(true)
				->delete($db->quoteName('#__vmmapicon_mapping'))
				->where($db->quoteName('api_id') . ' = ' . $apiId);
			$db->setQuery($query)->execute();

			$table   = $db->quoteName('#__vmmapicon_mapping');
			$columns = [
				$db->quoteName('api_id'),
				$db->quoteName('article_id'),
				$db->quoteName('alias'),
				$db->quoteName('category'),
			];

			$rows = [];
			foreach ($idMapping as $articleId => $values) {
				$rows[] = $apiId . ', ' . (int) $articleId . ', ' . $db->quote((string) $values['articleAlias']) . ', ' . $db->quote((string) $values['categoryAlias']);;
			}

			$chunkSize = 500;
			foreach (array_chunk($rows, $chunkSize) as $chunk) {
				$query = $db->getQuery(true)
					->insert($table)
					->columns($columns);
				foreach ($chunk as $row) {
					$query->values($row);
				}
				$db->setQuery($query)->execute();
			}

			$db->transactionCommit();
		} catch (\Throwable $e) {
			$db->transactionRollback();
			throw $e;
		}
	}
}

