<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   string   $name            Name of the input field.
 * @var   string   $value           Value attribute of the field.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 * @var   array    $inputType       Options available for this field.
 * @var   string   $apiResult       String of Api Result
 */


/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_vmmapicon.jsonselector');

$attributes = [
    !empty($class) ? 'class="form-control ' . $class . '"' : 'class="form-control' . '"',
];

?>

<div class="input-group">
       <div class="container-fluid p-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">API Field Mapper</h3>
            </div>
            <div class="card-body">
                <div id="api-mapper"></div>
                <div class="mt-3">
                    <h4>JSON Output</h4>
                    <pre id="json-output" class="border p-3 bg-light"></pre>
                </div>
            </div>
        </div>
    </div>
    <input
        type='hidden'
        name='apiResult'
        id='apiResult'
        value='<?= $apiResult ?>'
        >
    <input
        type="hidden"
        name="<?php echo $name; ?>"
        id="<?php echo $id; ?>"
        value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>"
        <?php echo implode(' ', $attributes); ?>
        >
</div>



