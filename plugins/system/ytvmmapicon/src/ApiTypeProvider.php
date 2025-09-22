<?php

namespace Joomla\Plugin\System\Ytvmmapicon;
use Joomla\CMS\Factory;
use Villaester\Component\Vmmapicon\Administrator\Helper\ApiHelper;


/**
 * Custom Type Providerwfsdf
 *
 * @see https://yootheme.com/support/yootheme-pro/joomla/developers-sources#add-custom-sources
 */
class ApiTypeProvider
{

    public static function get($id)
    {

        $model = Factory::getApplication()->bootComponent('com_vmmapicon')->getMVCFactory()->createModel('Api', 'Administrator');
        $item = $model->getItem($id);

        if (!$item) {
            return null;
        }

        $apiResponse = ApiHelper::getApiResult($item);
        $apiData = json_decode($apiResponse, true);

        // Gespeichertes Mapping aus der Komponente (bevorzugt)
        $storedMapping = $model->getMapping($id) ?: [];

        // Dynamisches Mapping aus dem ersten Eintrag der API-Response erzeugen (Fallback)
        $dynamicMapping = self::buildMappingFromFirstEntry($apiData);

        $item->mapping_fields = !empty($storedMapping) ? $storedMapping : $dynamicMapping;
        $item->api_data = $apiData;

        return $item;
    }

    // Erzeuge ein Mapping-Array anhand des ersten Eintrags der API-Response
    private static function buildMappingFromFirstEntry($apiData)
    {
        if ($apiData === null) {
            return [];
        }

        // Ersten Datensatz bestimmen und passenden JSON-Prefix setzen,
        // damit die Pfade am Root korrekt aufgelöst werden (z. B. data->0->...)
        $first  = null;
        $prefix = [];
        if (is_array($apiData)) {
            if (isset($apiData['data']) && is_array($apiData['data']) && !empty($apiData['data'])) {
                $first  = $apiData['data'][0];
                $prefix = ['data', '0'];
            } elseif (!empty($apiData) && array_values($apiData) === $apiData) { // numerisch indiziert
                $first  = $apiData[0];
                $prefix = ['0'];
            } else {
                $first  = $apiData; // assoziatives Array
                $prefix = [];
            }
        } else {
            $first  = $apiData; // Objekt oder Skalar
            $prefix = [];
        }

        $names = [];
        $mapping = [];
        self::flattenToMapping($first, $prefix, $mapping, $names);

        return $mapping;
    }

    // Rekursive Flatten-Funktion, die Mapping-Einträge erzeugt
    private static function flattenToMapping($data, array $prefix, array &$mapping, array &$names)
    {
        // Objekte wie Arrays behandeln
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            if (self::isAssoc($data)) {
                foreach ($data as $key => $value) {
                    $newPrefix = array_merge($prefix, [(string) $key]);
                    self::flattenToMapping($value, $newPrefix, $mapping, $names);
                }
            } else {
                // Liste/Array: nur ersten Eintrag für das Mapping verwenden
                if (!empty($data)) {
                    $newPrefix = array_merge($prefix, ['0']);
                    self::flattenToMapping($data[0], $newPrefix, $mapping, $names);
                }
            }
            return;
        }

        // Skalarer Wert: Mapping-Eintrag erzeugen
        $path = implode('->', $prefix);
        $type = self::detectFieldType($data);
        $yoothemeName = self::sanitizeFieldName($path);

        // Eindeutigkeit sicherstellen
        $baseName = $yoothemeName;
        $i = 1;
        while (isset($names[$yoothemeName])) {
            $yoothemeName = $baseName . '_' . $i;
            $i++;
        }
        $names[$yoothemeName] = true;

        $label = ucfirst(str_replace(['->', '_'], [' » ', ' '], $path));

        $mapping[] = [
            'yootheme_name' => $yoothemeName,
            'json_path'     => $path,
            'field_type'    => $type,
            'field_label'   => $label,
        ];
    }

    private static function isAssoc(array $arr)
    {
        if ($arr === []) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private static function detectFieldType($value)
    {
        if (is_bool($value)) {
            return 'Boolean';
        }
        if (is_int($value)) {
            return 'Int';
        }
        if (is_float($value)) {
            return 'Float';
        }
        // Arrays/Objekte, die hier nicht weiter aufgelöst wurden, als String serialisieren
        if (is_array($value) || is_object($value)) {
            // Arrays/Objekte als Liste von Strings ausgeben
            return 'listOf(String)';
        }
        return 'String';
    }

    private static function sanitizeFieldName($path)
    {
        // Pfadsegmente in einen validen GraphQL-Feldnamen umwandeln
        $name = preg_replace('/[^A-Za-z0-9_]+/', '_', str_replace('->', '__', $path));
        if ($name === '' || preg_match('/^[0-9]/', $name)) {
            $name = 'f_' . $name;
        }
        return $name;
    }
}
