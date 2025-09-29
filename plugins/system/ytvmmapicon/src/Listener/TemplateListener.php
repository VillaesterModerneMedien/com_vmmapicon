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
use YOOtheme\Config;


class TemplateListener
{

    public string $language;

    public function __construct(?Document $document)
    {
        $this->language = $document->language ?? 'de-de';
    }

	public static function initCustomizer( $config)
	{
		$config->merge([
			'templates' => [
				'com_vmmapicon.apisingle' => [
					'label' => 'Api Singleview Template',
					'fieldset' => [
						'default' => [
							'fields' => [

							],
						],
					],
				],

				'com_vmmapicon.apiblog' => [
					'label' => 'Api Blog Template',
					'fieldset' => [
						'default' => [
							'fields' => [

							],
						],
					],

				],
			],

		]);
	}


	public static function matchTemplate($view, $tpl)
    {
        if ($tpl) {
            return null;
        }

	    $layout = $view->getLayout();
	    $context = $view->get('context');


        // Nur Standard-Layouts matchen
        if ($layout && $layout !== 'default') {
            return null;
        }

        // Kontext-basierte Matches (empfohlen)
        if ($context === 'com_vmmapicon.apisingle' && $layout === 'default' && !$tpl) {
            // Debug
            @error_log('[ytvmmapicon] matchTemplate: matched com_vmmapicon.apisingle');
            return [
                'type' => $context,
                'params' => ['item' => $view->get('item')],
            ];
        }

	    if ($context === 'com_vmmapicon.apiblog' && $layout === 'default' && !$tpl) {
            // Debug
            @error_log('[ytvmmapicon] matchTemplate: matched com_vmmapicon.apiblog');
            return [
                'type' => $context,
                'params' => [
                    'items' => $view->get('items'),
                    'pagination' => $view->get('pagination'),
                ],
            ];
        }

        return null;
    }

}
