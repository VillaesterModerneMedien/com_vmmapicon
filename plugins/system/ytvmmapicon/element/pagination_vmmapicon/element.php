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

use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\PaginationObject;
use YOOtheme\Builder\Joomla\Source\apiHelper;

return [
    'transforms' => [
        'render' => function ($node, $params) {
            // Single api
            if (!isset($params['pagination'])) {
                $api = $params['item'] ?? ($params['api'] ?? false);

                if (!$api || !apiHelper::applyPageNavigation($api)) {
                    return false;
                }

                $params['pagination'] = [
                    'previous' => $api->prev
                        ? new PaginationObject($api->prev_label, '', null, $api->prev)
                        : null,
                    'next' => $api->next
                        ? new PaginationObject($api->next_label, '', null, $api->next)
                        : null,
                ];
            }

            if (is_callable($params['pagination'])) {
                $params['pagination'] = $params['pagination']();
            }

            if (is_array($params['pagination'])) {
                $node->props['pagination_type'] = 'previous/next';
                $node->props['pagination'] = $params['pagination'];
                return;
            }

            // api Index
            if (empty($params['pagination']) || $params['pagination']->pagesTotal < 2) {
                return false;
            }

            $list = $params['pagination']->getPaginationPages();

            $total = $params['pagination']->pagesTotal;
            $current = (int) $params['pagination']->pagesCurrent;
            $endSize = 1;
            $midSize = 3;
            $dots = false;

            $pagination = [];

            if ($list['previous']['active']) {
                $pagination['previous'] = $list['previous']['data'];
            }

            $list['start']['data']->text = 1;
            $list['end']['data']->text = $total;

            for ($n = 1; $n <= $total; $n++) {
                $active =
                    $n <= $endSize ||
                    ($current && $n >= $current - $midSize && $n <= $current + $midSize) ||
                    $n > $total - $endSize;

                if ($active || $dots) {
                    if ($active) {
                        $pagination[$n] =
                            $n === 1
                                ? $list['start']['data']
                                : ($n === $total
                                    ? $list['end']['data']
                                    : $list['pages'][$n]['data']);

                        $pagination[$n]->active = $n === $current;
                    } else {
                        $pagination[$n] = new PaginationObject(Text::_('&hellip;'));
                    }

                    $dots = $active;
                }
            }

            if ($list['next']['active']) {
                $pagination['next'] = $list['next']['data'];
            }

            $node->props['pagination'] = $pagination;
        },
    ],
];
