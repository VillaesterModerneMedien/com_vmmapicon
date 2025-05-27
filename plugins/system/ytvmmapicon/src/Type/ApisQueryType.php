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



namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Plugin\System\Ytvmmapicon\ApisTypeProvider;
use Villaester\Component\Vmmapicon\Site\Helper\RouteHelper;

class ApisQueryType
{
    public static function config()
    {
        return [

            'fields' => [

                'apis' => [
                    'type' => [
                        'listOf' => 'Apis',
                    ],
                    'metadata' => [
                        'label' => 'Apis (List)',
                        'view' => ['com_vmmapicon.apis'],
                        'group' => 'Apis',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::apis',
                    ],
                ],

            ]

        ];
    }

    /**
     * Retrieve a list of remapped real estates.
     *
     * @param   mixed  $root  The root parameter.
     *
     * @return array The array of remapped real estates.
     */
    public static function apis($root)
    {
        $app = Factory::getApplication();

        // Get the FieldsModelField, we need it in a sec
        $mvcFactory = $app->bootComponent('com_vmmapicon')->getMVCFactory();
        /** @var \Villaester\Component\Vmmapicon\Site\Model\ApisModel $apisModel */
        $apisModel = $mvcFactory->createModel('Apis', 'Site', ['ignore_request' => true]);
        $apis = $apisModel->getItems(true);

        $apisRemapped = [];

        $menu = Factory::getApplication()->getMenu()->getActive();

        foreach ($apis as $api) {

            $Itemid = $menu->id;
            $route = RouteHelper::getApiRoute($api->{'@id'});
            $url = Route::_('index.php?option=com_vmmapicon&view=api&apiID=' . (int)$api->{'@id'} . '&Itemid=' . $Itemid);

            $apisRemapped[] = [
                'id' => $api->{'@id'},
                'title' => $api->title,
                'type' => $api->{'@xsi.type'},
                'externalId' => $api->externalId,
                'creationDate' => $api->{'@creation'},
                'modifiedDate' => $api->{'@modification'},
                'apiRoute' => $route,
                'apiUrl' => $url,
                'street' => $api->address->street,
                'houseNumber' => $api->address->houseNumber,
                'zipCode' => $api->address->postcode,
                'city' => $api->address->city,
                'livingSpace' => $api->livingSpace,
                'numberOfRooms' => $api->numberOfRooms,
                'price' => $api->price->value,
                'currency' => $api->price->currency,
                'apiState' => $api->realEstateState,
                'titlePicture' => $api->titlePicture,
                'titlePictureUrl' => strstr($api->titlePicture->urls[0]->url[0]->{'@href'}, '/ORIG', true),
            ];
        }

        return $apisRemapped;


    }

}
