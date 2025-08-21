<?php
namespace Villaester\Component\Vmmapicon\Administrator\Field\Jsonselector;

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Object\CMSObject;
use Joomla\Utilities\ArrayHelper;
use Villaester\Component\Vmmapicon\Administrator\Helper\ApiHelper;
use Villaester\Component\Vmmapicon\Administrator\Helper\VmmapiconHelper;

class JsonselectorField extends FormField
{
	protected $layout = 'vmm.form.field.jsonselector';
	protected $type = 'Jsonselector';
	protected $renderLayout = 'vmm.form.renderfield';
	protected $renderLabelLayout = 'vmm.form.renderlabel';

	protected function _getJsonKeys($json){
		if (is_string($json)) {
			$array = json_decode($json, true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				return [];
			}
		} else {
			$array = $json;
		}

		$allKeys = [];
		$this->collectPathsRecursive($array, $allKeys);
		$uniqueKeys = array_unique($allKeys);

		return $uniqueKeys;
	}

	private function collectPathsRecursive($data, &$paths, $currentPath = '') {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				if (is_numeric($key) && $key > 0) {
					continue;
				}

				if (is_numeric($key)) {
					$newPath = $currentPath;
				} else {
					$newPath = $currentPath === '' ? $key : $currentPath . '->' . $key;
					$paths[] = $newPath;
				}

				if (is_array($value) || is_object($value)) {
					$this->collectPathsRecursive($value, $paths, $newPath);
				}
			}
		} elseif (is_object($data)) {
			foreach ($data as $key => $val) {
				$newPath = $currentPath === '' ? $key : $currentPath . '->' . $key;
				$paths[] = $newPath;

				if (is_array($val) || is_object($val)) {
					$this->collectPathsRecursive($val, $paths, $newPath);
				}
			}
		}
	}

	protected function getInput()
	{
		$apiHelper = new ApiHelper();
		$vmmApiconHelper = new VmmapiconHelper();
		$apiConfig = $vmmApiconHelper->getApiConfig();

		$data = $this->getLayoutData();
		$keysFormatted = $this->_getJsonKeys($apiHelper->getApiResult($apiConfig));

		$data['apiResult'] = $keysFormatted;
		$data['fieldName'] = $this->name;
		$data['fieldId'] = $this->id;
		$data['fieldValue'] = $this->value;

		return $this->getRenderer($this->layout)->render($data);
	}
}