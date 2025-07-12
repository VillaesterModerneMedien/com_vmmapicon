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

        $layout = $view->getLayout();
        $context = $view->get('context');

        // match context and layout from view object

        if ($context === 'com_vmmapicon.api' && $layout === 'default' && !$tpl) {

            // return type, query and parameters of the matching view
            return [
                'type' => $context,
                'params' => ['item' => $view->get('item')],
            ];
        }

        // match context and layout from view object
        if ($context === 'com_vmmapicon.apis' && $layout === 'default' && !$tpl) {
$a = 'bla';
            // return type, query and parameters of the matching view
            return [
                'type' => $context,
                'params' => [
                    'items' => $view->get('items'),
                    'pagination' => $view->get('pagination'),
                ],
            ];
        }
    }
}
