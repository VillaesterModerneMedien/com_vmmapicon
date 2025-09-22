<?php

namespace Villaester\Plugin\System\Vmmapiconyt\Listener;

class TemplateListener
{
    public function matchTemplate($view, $tpl)
    {
        $layout  = $view->getLayout();
        $context = $view->get('context');

        // Match VMMapicon single API ITEM view as a YOOtheme template source
        if ($context === 'com_vmmapicon.apiitem' && $layout === 'default' && !$tpl) {
            return [
                'type'   => $context,
                'params' => ['item' => $view->get('item')],
            ];
        }

        return null;
    }
}
