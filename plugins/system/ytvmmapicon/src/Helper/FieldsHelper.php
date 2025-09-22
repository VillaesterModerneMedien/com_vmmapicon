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

namespace Joomla\Plugin\System\Ytvmmapicon\Helper;


use Joomla\CMS\Document\Document;
use Joomla\CMS\Language\Text;

class FieldsHelper
{
    public static function setFieldMappings($api)
    {

        $apiProperties = get_object_vars($api);
        $apiObjectKeys = array_keys($apiProperties);


        /* ---------- */
        /* Remap all object keys to a new array */
        /* ---------- */

        $apiRemapped = [];

        foreach ($apiObjectKeys as $key) {
            if(str_contains('@', $key)) {
                $apiRemapped[$key] = $api->{$key};
            }
            $apiRemapped[$key] = $api->{$key};
        }


        /* ---------- */
        /* Remap Translatable String Fields */
        /* ---------- */

        $remappedTranslatable = [
            'heatingType', 'heatingTypeEnev2014', 'apartmentType', 'condition', 'buildingEnergyRatingType'
        ];
        $remappedTranslatableStrings = [
            'COM_YTVMMAPICON_HEATING_TYPE_', 'COM_YTVMMAPICON_HEATING_TYPE_', 'COM_YTVMMAPICON_APARTMENT_TYPE_', 'COM_YTVMMAPICON_CONDITION_', 'COM_YTVMMAPICON_ENERGY_CONSUMPTION_'
        ];
        foreach($remappedTranslatable as $key => $value)
        {
            if(property_exists($api, $value))
            {
                $apiRemapped[$value] = Text::_($remappedTranslatableStrings[$key] . $api->{$value});
            }
        }


        /* ---------- */
        /* Remap Boolean Fields */
        /* ---------- */

        foreach ($apiRemapped as $key => $value) {
            if($value === 'YES' || $value == 'true') {
                $apiRemapped[$key] = Text::_('COM_YTVMMAPICON_YES');
            }
            if($value === 'NO' || $value == 'false') {
                $apiRemapped[$key] =  Text::_('COM_YTVMMAPICON_NO');
            }
            if($value === 'NOT_APPLICABLE') {
                $apiRemapped[$key] =  Text::_('COM_YTVMMAPICON_NOT_APPLICABLE');
            }
            if($value === 'NOT_AVAILABLE') {
                $apiRemapped[$key] =  Text::_('COM_YTVMMAPICON_NOT_AVAILABLE');
            }
            if($value === 'NO_INFORMATION') {
                $apiRemapped[$key] =  Text::_('COM_YTVMMAPICON_NO_INFORMATION');
            }
        }


        /* ---------- */
        /* Remap address data to new values */
        /* ---------- */

        $apiRemapped['street'] = $api->address->street;
        $apiRemapped['houseNumber'] = $api->address->houseNumber;
        $apiRemapped['zipCode'] = $api->address->postcode;
        $apiRemapped['city'] = $api->address->city;
        $apiRemapped['lat'] = $api->address->wgs84Coordinate->latitude;
        $apiRemapped['lng'] = $api->address->wgs84Coordinate->longitude;
        $apiRemapped['latlng'] = $api->address->wgs84Coordinate->latitude . ',' . $api->address->wgs84Coordinate->longitude;
        unset($apiRemapped['address']);


        /* ---------- */
        /* Remap Courtage data to new values */
        /* ---------- */

        if($api->courtage->hasCourtage === 'YES')
        {
            $apiRemapped['courtage'] = $api->courtage->courtage ?? '';
            $apiRemapped['courtageNote'] = $api->courtage->courtageNote ?? '';
        }


        /* ---------- */
        /* Remap Energy data to new values, generate comma separated string */
        /* ---------- */

        if (isset($api->energySourcesEnev2014) && is_object($api->energySourcesEnev2014)) {

            if (isset($api->energySourcesEnev2014->energySourceEnev2014)) {

                $energySources = $api->energySourcesEnev2014->energySourceEnev2014;

                if (is_array($energySources)) {
                    $maxValues = count($energySources);
                    $counter = 0;
                    $energySourcesEnev2014 = '';
                    foreach ($energySources as $value) {
                        $counter++;
                        $energySourcesEnev2014 .= Text::_('COM_YTVMMAPICON_ENERGY_SOURCES_ENEV_2014_' . $value) . ', ';
                        if($counter === $maxValues) {
                            $energySourcesEnev2014 .= Text::_('COM_YTVMMAPICON_ENERGY_SOURCES_ENEV_2014_' . $value);
                        }
                    }
                } else {
                    $energySourcesEnev2014 = Text::_('COM_YTVMMAPICON_ENERGY_SOURCES_ENEV_2014_' . $energySources);
                }
            }

            $apiRemapped['energySourcesEnev2014'] = $energySourcesEnev2014;
        }


        /* ---------- */
        /* Remap Firing data to new values, generate comma separated string */
        /* ---------- */

        if (isset($api->firingTypes)) {

            if (isset($api->firingTypes[0]->firingType)) {

                $firingTypes = $api->firingTypes[0]->firingType;
                if (is_array($firingTypes)) {
                    $maxValues = count($firingTypes);
                    $counter = 0;
                    $firingTypeLabels = '';
                    foreach ($firingTypes as $value) {
                        $counter++;
                        $firingTypeLabels .= Text::_('COM_YTVMMAPICON_ENERGY_SOURCES_ENEV_2014_' . $value) . ', ';
                        if($counter === $maxValues) {
                            $firingTypeLabels .= Text::_('COM_YTVMMAPICON_ENERGY_SOURCES_ENEV_2014_' . $value);
                        }
                    }
                } else {
                    $firingTypeLabels = Text::_('COM_YTVMMAPICON_ENERGY_SOURCES_ENEV_2014_' . $firingTypes);
                }

            }

            $apiRemapped['firingTypes'] = $firingTypeLabels;
        }


        /* ---------- */
        /* Remap Energy Certificate data to new values */
        /* ---------- */

        if (isset($api->energyCertificate)) {

            if (isset($api->energyCertificate->energyCertificateCreationDate)) {
                $apiRemapped['energyCertificateCreationDate'] = $api->energyCertificate->energyCertificateCreationDate;
            }
            if (isset($api->energyCertificate->energyEfficiencyClass)) {
                $apiRemapped['energyEfficiencyClass'] = $api->energyCertificate->energyEfficiencyClass;
            }
            unset($apiRemapped['energyCertificate']);
        }


        $apiRemapped['attachments'] = json_encode($api->attachments);
        $apiRemapped['attachments2'] = json_encode($api->attachments);

        return $apiRemapped;
    }

}
