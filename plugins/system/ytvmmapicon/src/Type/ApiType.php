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

//
namespace Joomla\Plugin\System\Ytvmmapicon\Type;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;

use Joomla\CMS\Router\Route;
use Joomla\Component\Categories\Administrator\Model\CategoryModel;
use Joomla\Component\Fields\Administrator\Model\FieldModel;
use \Joomla\Language\Text;
use Joomla\Plugin\System\Ytvmmapicon\Helper\FieldsHelper;
use Joomla\String\StringHelper;
use VmmdatabaseNamespace\Component\Vmmdatabase\Site\Model\DatasetModel;


class ApiType
{

    public function setFields($fieldname, $fieldtype, $label, $tab)
    {
        $array = [
            $fieldname => [
                'type' => $fieldtype,
                'metadata' => [
                    'label' => $label,
                    'group' => $tab
                ],
                'extensions' => [
                    'call' => [
                        'func' => __CLASS__ . '::resolve',
                        'args' => [
                            'fieldname' => $fieldname,
                        ]
                    ]

                ]

            ],
        ];

        return $array;
    }


    /**
     * @return array
     */
    public static function configOld()
    {

        return [
            'fields' => [
                // Generic Fields
                'externalId' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anbieterobjektnummer',
                        'value' => '',
                    ],
                ],
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Exposeüberschrift',
                        'value' => '',
                    ],
                ],
                'street' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Straße',
                        'value' => '',
                    ],
                ],
                'houseNumber' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Hausnummer',
                        'value' => '',
                    ],
                ],
                'zipCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'PLZ',
                        'value' => '',
                    ],
                ],
                'city' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Ort',
                        'value' => '',
                    ],
                ],
                'searchField1' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'API-Suche-Felder',
                        'value' => '',
                    ],
                ],
                'searchField2' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'API-Suche-Felder',
                        'value' => '',
                    ],
                ],
                'searchField3' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'API-Suche-Felder',
                        'value' => '',
                    ],
                ],
                'groupNumber' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Objektgruppierung',
                        'value' => '',
                    ],
                ],
                'descriptionNote' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Objektbeschreibung',
                        'value' => '',
                    ],
                ],
                'locationNote' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Lage',
                        'value' => '',
                    ],
                ],
                'otherNote' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Sonstige Angaben',
                        'value' => '',
                    ],
                ],
                'contactId' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kontaktperson',
                        'value' => '',
                    ],
                ],
                'condition' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Objektzustand',
                        'value' => '',
                    ],
                ],
                'constructionYear' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Baujahr',
                        'value' => null,
                    ],
                ],
                'constructionYearUnknown' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Baujahr unbekannt',
                        'value' => false,
                    ],
                ],
                'latitude' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Geokoordinaten (Breitengrad)',
                        'value' => null,
                    ],
                ],
                'longitude' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Geokoordinaten (Längengrad)',
                        'value' => null,
                    ],
                ],
                'latlng' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Lat / Lng combined',
                        'value' => null,
                    ],
                ],
                'creationDate' => [
                    'type' => 'String', // or Date depending on how you want to handle dates
                    'metadata' => [
                        'label' => 'Expose-Erstellungsdatum',
                        'filters' => ['date'],
                        'value' => '',
                    ],
                ],
                'lastModificationDate' => [
                    'type' => 'String', // or Date
                    'metadata' => [
                        'label' => 'Expose-Änderungsdatum',
                        'filters' => ['date'],
                        'value' => '',
                    ],
                ],

                // Apartment

                'apartmentType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Wohnungskategorie',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'floor' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Etage',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'lift' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Aufzug',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'cellar' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Keller',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfParkingSpaces' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Parkflächenanzahl',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'lastRefurbishment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Letzte Modernisierung',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'interiorQuality' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Qualität der Ausstattung',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'heatingType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Heizungsart',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'firingTypes' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Befeuerungsarten',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'BuildingEnergyRatingType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energieausweistyp',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'thermalCharacteristic' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energieverbrauchskennwert',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'energyConsumptionContainsWarmWater' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energieverbrauch enthält Warmwasser',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'energyCertificateCreationDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energieausweis-Erstellungsdatum',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'energyEfficiencyClass' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energieeffizienzklasse',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'heatingTypeEnev2014' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Heizungsart nach EnEV 2014',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'energySourcesEnev2014' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energiequellen nach EnEV 2014',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfFloors' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Etagenzahl',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'usableFloorSpace' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Nutzfläche',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfBedRooms' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl Schlafzimmer',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfBathRooms' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl Badezimmer',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'guestToilet' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gäste-WC',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'parkingSpaceType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Parkplatz',
                        'group' => 'Apartment',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Miete (Rent)

                'baseRent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kaltmiete',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'totalRent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Warmmiete',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'serviceCharge' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Nebenkosten',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'deposit' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kaution o. Genossenschaftsanteile',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'heatingCosts' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Heizkosten',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'heatingCostsInServiceCharge' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Heizkosten sind in Nebenkosten enthalten',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'petsAllowed' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Haustiere',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'parkingSpacePrice' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Stellplatzmiete',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'livingSpace' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Wohnfläche',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'livingSpaceFrom' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Wohnfläche von',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'livingSpaceTo' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Wohnfläche bis',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfRooms' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Zimmerzahl',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'builtInKitchen' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Einbauküche',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'balcony' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Balkon/Terrasse',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'garden' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gartenbenutzung',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'courtage' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Provisionspflichtig',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'courtageNote' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Provisionshinweis',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'freeFrom' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Frei ab',
                        'group' => 'Miete (Rent)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],


                // Kaufen (Buy)

                'value' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kaufpreis',
                        'group' => 'Kaufen (Buy)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'currency' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Währung',
                        'group' => 'Kaufen (Buy)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'marketingType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Vermarktungstyp',
                        'group' => 'Kaufen (Buy)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'priceIntervalType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Preisintervalltyp',
                        'group' => 'Kaufen (Buy)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Haus (House)

                'plotArea' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Grundstücksfläche',
                        'group' => 'Haus (House)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'buildingType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gebäudetyp',
                        'group' => 'Haus (House)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Gewerbeobjekte (Commercial)

                'commercializationType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Vermarktungstyp',
                        'group' => 'Gewerbeobjekte (Commercial)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'totalFloorSpace' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gesamtfläche',
                        'group' => 'Gewerbeobjekte (Commercial)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'netFloorSpace' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Nettofläche',
                        'group' => 'Gewerbeobjekte (Commercial)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'minDivisible' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Mindestteilbarkeit',
                        'group' => 'Gewerbeobjekte (Commercial)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'additionalCosts' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Zusatzkosten',
                        'group' => 'Gewerbeobjekte (Commercial)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Spezialgewerbe (Special Purpose)

                'shortDescription' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kurzbeschreibung',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'trialLivingPossible' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Probewohnen möglich',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'barrierFree' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Barrierefrei',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfLookedAfterApartments' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl betreuter Wohnungen',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfNursingPlaces' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl Pflegeplätze',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                'handicappedAccessible' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Barrierefrei',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'guestApartmentsAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gästeapartments verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'restaurantAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Restaurant verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'cookingFacilitiesAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kochmöglichkeiten vorhanden',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'ownFurniturePossible' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Eigene Möbel möglich',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'cleaningServiceAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Reinigungsservice verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'shoppingFacilitiesAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Einkaufsmöglichkeiten vorhanden',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'security24Hours' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Sicherheitsdienst 24 Stunden',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'culturalProgramAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kulturprogramm verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'leisureActivitiesAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Freizeitaktivitäten verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'religiousOfferingsAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Religiöse Angebote verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'balconyAvailable' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Balkon verfügbar',
                        'group' => 'Spezialgewerbe (Special Purpose)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Grundstück (Site)

                'recommendedUseTypes' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Empfohlene Nutzungstypen',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'tenancy' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Pacht',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'shortTermConstructible' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kurzfristig bebaubar',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'buildingPermission' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Baugenehmigung',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'demolition' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Abriss',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'siteDevelopmentType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Erschließungstyp',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'siteConstructibleType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Bebaubarkeitstyp',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'grz' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Grundflächenzahl (GRZ)',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'gfz' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Geschossflächenzahl (GFZ)',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'leaseInterval' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Pachtintervall',
                        'group' => 'Grundstück (Site)',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Garage

                'garageType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Garagentyp',
                        'group' => 'Garage',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'lengthGarage' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Länge',
                        'group' => 'Garage',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'widthGarage' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Breite',
                        'group' => 'Garage',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'heightGarage' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Höhe',
                        'group' => 'Garage',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // Zwangsversteigerung

                'marketValue' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Marktwert',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'lowestBid' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Mindestgebot',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'recurrenceAppointment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Wiederholungstermin',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'dateOfAuction' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Versteigerungsdatum',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'lastChangeDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Letztes Änderungsdatum',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'cancellationDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Stornierungsdatum',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'recordationDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Eintragungsdatum',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'area' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Fläche',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'auctionObjectType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Auktionsobjekttyp',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'countyCourt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Amtsgericht',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'fileReferenceAtCountyCourt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Aktenzeichen beim Amtsgericht',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfFolio' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Grundbuchblattnummer',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'splittingAuction' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Teilungsversteigerung',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'owner' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Eigentümer',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'energyPerformanceCertificate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Energieausweis vorhanden',
                        'group' => 'Zwangsversteigerung',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                // WG-Zimmer

                'roomSize' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Zimmergröße',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'Bodenbelag' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Bodenbelag',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'calculatedTotalRent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Berechnete Gesamtmiete',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'calculatedTotalRentScope' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Umfang der berechneten Gesamtmiete',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'freeUntil' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Frei bis',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'minimumTermOfLease' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Mindestmietdauer',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'totalSpace' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gesamtfläche',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],

                'numberOfMaleFlatMates' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl der männlichen Mitbewohner',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfFemaleFlatMates' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl der weiblichen Mitbewohner',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'ageOfFlatMatesFrom' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Alter der Mitbewohner von',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'ageOfFlatMatesTo' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Alter der Mitbewohner bis',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'ageOfRequestedFrom' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gewünschtes Alter von',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'ageOfRequestedTo' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gewünschtes Alter bis',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'numberOfRequestedFlatMates' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Anzahl der gesuchten Mitbewohner',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'oven' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Ofen',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'refrigerator' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Kühlschrank',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'stove' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Herd',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'dishwasher' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Geschirrspüler',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'washingMachine' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Waschmaschine',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'bathHasWc' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Bad mit WC',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'bathHasShower' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Bad mit Dusche',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'bathHasTub' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Bad mit Badewanne',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'internetConnection' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Internetanschluss',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'smokingAllowed' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Rauchen erlaubt',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'requestedGender' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Geschlecht des gesuchten Mitbewohners',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'furnishing' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Möblierung',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'tvConnection' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Fernsehanschluss',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'telephoneConnection' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Telefonanschluss',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'parkingSituation' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Parksituation',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'flatShareSize' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Größe der WG',
                        'group' => 'WG-Zimmer',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                ],
                'attachments' => [
                    'type' =>  ['listOf' => 'Apiimages'],
                    'metadata' => [
                        'label' => 'Fotos',
                        'filters' => ['limit'],
                        'value' => '',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::images',
                    ],
                ],
            ],
            'metadata' => [
                'type' => true,
                'label' => 'Api',
                'value' => '', // Falls benötigt
            ]
        ];
    }

    public static function configOld2()
    {

        $test = 'not set';

        $db    = Factory::getContainer()->get('DatabaseDriver');

        $app       = Factory::getApplication();

        $session = $app->getSession();
        $datasetId = $session->get('com_vmmapicon.edit.dataset.data.id');

        // Get the FieldsModelField, we need it in a sec
        $mvcFactory = $app->bootComponent('com_vmmapicon')->getMVCFactory();

        /** @var DatasetModel $datasetModel */
        $datasetModel = $mvcFactory->createModel('Dataset', 'Site', ['ignore_request' => true]);

        $currentCategory = 0;

        if(is_object($datasetModel->getItem($datasetId)))
        {
            $currentCategory = $datasetModel->getItem($datasetId)->catid;
        }



        return [
            'fields' => [

            ],
            'metadata' => [
                'type' => true,
                'label' => 'Api',
                'value' => '', // Falls benötigt
            ]
        ];
    }

    public static function images($data)
    {
        $attachments = $data['attachments'];
        $attachments = json_decode($attachments, true);

        return $attachments;
    }
}
