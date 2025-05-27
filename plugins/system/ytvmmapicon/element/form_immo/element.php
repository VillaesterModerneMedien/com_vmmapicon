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

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return $node->props['exposeeContact'] != '';
        },
    ],

    'updates' => [
        '2.8.0-beta.0.13' => function ($node) {
            if (Arr::get($node->props, 'text_size') && !Arr::get($node->props, 'text_style')) {
                $node->props['text_style'] = Arr::get($node->props, 'text_size');
            }
            unset($node->props['text_size']);
        },

        '1.20.0-beta.4' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },
    ],
];
