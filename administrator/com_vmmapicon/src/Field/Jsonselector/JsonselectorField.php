<?php
namespace Villaester\Component\Vmmapicon\Administrator\Field\Jsonselector;

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Object\CMSObject;
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

    protected function getInput()
    {
		$apiHelper = new ApiHelper();
	    $vmmApiconHelper = new VmmapiconHelper();
		/** @var CMSObject $apiConfig */
		$apiConfig = $vmmApiconHelper->getApiConfig();

		$data = $this->getLayoutData();
		$data['apiResult'] = $apiHelper->getApiResult($apiConfig);
		
	    return $this->getRenderer($this->layout)->render($data);
    }
}