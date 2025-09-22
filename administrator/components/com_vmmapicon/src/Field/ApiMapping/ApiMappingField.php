<?php
namespace Villaester\Component\Vmmapicon\Administrator\Field\ApiMapping;

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Villaester\Component\Vmmapicon\Administrator\Helper\ApiHelper;
use Villaester\Component\Vmmapicon\Administrator\Helper\VmmapiconHelper;

class ApiMappingField extends FormField
{
    protected $layout = 'vmm.form.field.apimapping';
    protected $type = 'ApiMapping';
    protected $renderLayout = 'vmm.form.renderfield';
    protected $renderLabelLayout = 'vmm.form.renderlabel';

    protected function getInput()
    {
        $apiConfig = VmmapiconHelper::getApiConfig();
        if (!$apiConfig) {
            $apiConfig = (object) [];
        }

        $data = $this->getLayoutData();

        // Get raw API result (robust against missing/invalid config)
        try {
            $rawApiResult = ApiHelper::getApiResult($apiConfig);
        } catch (\Throwable $e) {
            $rawApiResult = '';
        }

        // Parse to keys for the selector
        $keysFormatted = $this->getJsonKeysFromAny($rawApiResult);

        $data['apiResult']  = $keysFormatted;
        $data['fieldName']  = $this->name;
        $data['fieldId']    = $this->id;
        $data['fieldValue'] = $this->value;

        return $this->getRenderer($this->layout)->render($data);
    }

    private function getJsonKeysFromAny($jsonOrArray)
    {
        if (is_string($jsonOrArray)) {
            $array = json_decode($jsonOrArray, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
        } else {
            $array = $jsonOrArray;
        }

        $allKeys = [];
        $this->collectPathsRecursive($array, $allKeys);
        return array_values(array_unique($allKeys));
    }

    private function collectPathsRecursive($data, array &$paths, string $currentPath = ''): void
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_numeric($key) && $key > 0) {
                    continue;
                }

                $newPath = is_numeric($key)
                    ? $currentPath
                    : ($currentPath === '' ? (string) $key : $currentPath . '->' . $key);

                if (!is_numeric($key)) {
                    $paths[] = $newPath;
                }

                if (is_array($value) || is_object($value)) {
                    $this->collectPathsRecursive($value, $paths, $newPath);
                }
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $val) {
                $newPath = $currentPath === '' ? (string) $key : $currentPath . '->' . $key;
                $paths[] = $newPath;
                if (is_array($val) || is_object($val)) {
                    $this->collectPathsRecursive($val, $paths, $newPath);
                }
            }
        }
    }
}
