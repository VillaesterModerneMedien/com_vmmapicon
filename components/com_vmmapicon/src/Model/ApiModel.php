<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Villaester\Component\Vmmapicon\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseModel;
use Villaester\Component\Vmmapicon\Site\Helper\VmmapiconHelper;

/**
 * Model für die Single-Api Ansicht.
 * Lädt die Daten der gewählten API anhand der Request-Parameter.
 *
 * @since  1.0.0
 */
class ApiModel extends BaseModel
{
    /**
     * Liefert die Antwort der ausgewählten API.
     * - Erwartet entweder `apiID` (integer) oder `selectedApi` (z. B. "apis0").
     * - Setzt `selectedApi` in den Input, damit der bestehende Helper verwendet werden kann.
     *
     * @return mixed String mit Response oder strukturierte Daten, abhängig von der API
     */
    public function getItem()
    {
        $app   = Factory::getApplication();
        $input = $app->input;

        $selectedApi = $input->getString('selectedApi');
        $apiId       = $input->getInt('apiID');

        if (!$selectedApi && $apiId !== 0) {
            // Konvertiere numerische ID in den erwarteten Key der Komponenten-Parameter (z. B. "apis3")
            $selectedApi = 'apis' . $apiId;
            $input->set('selectedApi', $selectedApi);
        }

        // Fallback: Wenn weiterhin nichts gesetzt ist, breche ab
        if (!$selectedApi) {
            return null;
        }

        // Nutzt die bestehende Logik, die den Input liest
        return VmmapiconHelper::getApiData();
    }
}
