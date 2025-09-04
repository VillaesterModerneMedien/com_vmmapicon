<?php

namespace Villaester\Plugin\System\Vmmapiconyt\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Response\JsonResponse;
use Villaester\Plugin\System\Vmmapiconyt\VmmapiconApiProvider;

class SourceController
{
    public function apis()
    {
        try {
            $provider = new VmmapiconApiProvider();
            $apis = $provider->getApis();

            $response = new JsonResponse($apis);
            $response->send();

        } catch (\Exception $e) {
            $response = new JsonResponse(['error' => $e->getMessage()], 500);
            $response->send();
        }
    }
}
