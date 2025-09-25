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


namespace Joomla\Plugin\System\Ytvmmapicon\Listener;

use Joomla\CMS\Document\Document;
use Joomla\CMS\Language\Text;

class TemplateListener
{

    public string $language;

    public function __construct(?Document $document)
    {
        $this->language = $document->language ?? 'de-de';
    }
    public static function matchTemplate($view, $tpl)
    {
        if ($tpl) {
            return null;
        }

        $layout = method_exists($view, 'getLayout') ? $view->getLayout() : null;
        $viewName = method_exists($view, 'getName') ? $view->getName() : null;
        $viewName = $viewName ? strtolower($viewName) : null;

        // Standard-Layouts matchen
        if ($layout && $layout !== 'default') {
            return null;
        }

        // Api-Single (Element per itemId)
        if ($viewName === 'apisingle') {
            return [
                'type' => 'com_vmmapicon.apisingle',
                'params' => ['item' => $view->get('item')],
            ];
        }

        // Api-Blog (Liste)
        if ($viewName === 'apiblog') {
            return [
                'type' => 'com_vmmapicon.apiblog',
                'params' => ['items' => $view->get('items')],
            ];
        }

        return null;
    }

    public static function registerTemplates(array $templates): array
    {
        // Registriere die beiden Template-Typen für den Builder-Dialog
        $templates['com_vmmapicon.apiblog'] = [
            'label' => Text::_('COM_VMMAPICON_APIBLOG_VIEW_DEFAULT_TITLE'),
            'group' => 'VMMapicon',
            'icon'  => 'database',
        ];
        $templates['com_vmmapicon.apisingle'] = [
            'label' => Text::_('COM_VMMAPICON_APISINGLE_VIEW_DEFAULT_TITLE'),
            'group' => 'VMMapicon',
            'icon'  => 'file-text',
        ];
        return $templates;
    }
}
