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
    /**
     * Name of the layout being used to render the field
     *
     * @var    string
     * @since  4.0.0
     */
    protected $layout = 'vmm.form.field.jsonselector';

    /**
     * The form field type.
     *
     * @var    string
     * @since  1.7.0
     */
    protected $type = 'Jsonselector';

	/**
	 * Layout to render the form field
	 *
	 * @var  string
	 */
	protected $renderLayout = 'vmm.form.renderfield';

	/**
	 * Layout to render the label
	 *
	 * @var  string
	 */
	protected $renderLabelLayout = 'vmm.form.renderlabel';

	protected function _getJsonKeys($json){
		// JSON dekodieren falls es ein String ist
		if (is_string($json)) {
			$array = json_decode($json, true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				return [];
			}
		} else {
			$array = $json;
		}

		// Alle Keys rekursiv sammeln
		$allKeys = [];
		$this->collectKeysRecursive($array, $allKeys);

		// Unique Keys zurückgeben
		$uniqueKeys = array_unique($allKeys);

		return $uniqueKeys;
	}

	/**
	 * Rekursive Hilfsmethode zum Sammeln aller Keys
	 */
	private function collectKeysRecursive($data, &$keys) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				// Nur String-Keys hinzufügen (keine numerischen Array-Indizes)
				if (is_string($key)) {
					$keys[] = $key;
				}

				// Rekursiv in verschachtelte Strukturen schauen
				if (is_array($value) || is_object($value)) {
					$this->collectKeysRecursive($value, $keys);
				}
			}
		} elseif (is_object($value)) {
			foreach ($value as $key => $val) {
				$keys[] = $key;
				if (is_array($val) || is_object($val)) {
					$this->collectKeysRecursive($val, $keys);
				}
			}
		}
	}

    protected function getInput()
    {
		$apiHelper = new ApiHelper();
	    $vmmApiconHelper = new VmmapiconHelper();
		/** @var CMSObject $apiConfig */
		$apiConfig = $vmmApiconHelper->getApiConfig();

		$data = $this->getLayoutData();
        $keysFormatted = $this->_getJsonKeys($apiHelper->getApiResult($apiConfig));
		$data['apiResult'] = $keysFormatted;

	    return $this->getRenderer($this->layout)->render($data);
    }
}