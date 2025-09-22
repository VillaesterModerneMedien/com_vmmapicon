<?php

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class SourceControllerDataset
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function datasets(Request $request, Response $response)
    {
		
		$queryParams = $request->getQueryParams();
		$id = $queryParams['ids'][0];
		
        $titles = [];

        $dataset = DatasetTypeProvider::get($id);
        $titles[$dataset->id] = $dataset->title;

        return $response->withJson((object) $titles);
    }
}
