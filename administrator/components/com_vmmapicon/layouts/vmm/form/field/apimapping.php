<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

try {
    $wa->useScript('com_vmmapicon.apimapping');
} catch (Exception $e) {
    // No fallback: rely on Web Asset only
}

extract($displayData);

$decodedApi = [];
if (!empty($apiResult)) {
    $decodedApi = $apiResult;
}

// Existing subform data
$existingData = [];
if (!empty($fieldValue) && is_string($fieldValue)) {
    $existingData = json_decode($fieldValue, true) ?: [];
}

$attributes = [
    !empty($class) ? 'class="form-control ' . $class . '"' : 'class="form-control"',
];
?>

<div class="subform-container">
    <div class="container-fluid p-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= Text::_('COM_VMMAPICON_APIMAP_HEADING'); ?></h5>
                <button type="button" class="btn btn-success btn-sm" onclick="window.ApiMapping ? ApiMapping.addRow() : void(0)">
                    <i class="fas fa-plus"></i> <?= Text::_('COM_VMMAPICON_APIMAP_ADD_FIELD'); ?>
                </button>
            </div>
            <div class="card-body">
                <div id="subform-rows" class="subform-rows"></div>
            </div>
        </div>
    </div>

    <input
        type="hidden"
        name="<?php echo $fieldName; ?>"
        id="<?php echo $fieldId; ?>"
        value='<?= htmlspecialchars($fieldValue ?: '{}'); ?>'
        <?php echo implode(' ', $attributes); ?>
    >
</div>

    

<script>
    window.apiMappingConfig = {
        apiData: <?= json_encode($decodedApi) ?>,
        fieldName: '<?= $fieldName ?>',
        fieldId: '<?= $fieldId ?>',
        existingData: <?= json_encode($existingData) ?>,
        selectorsFieldName: 'jform[api_selectors]',
        lang: {
            jsonPathLabel: '<?= Text::_('COM_VMMAPICON_APIMAP_JSON_PATH'); ?>',
            jsonPathPlaceholder: '<?= Text::_('COM_VMMAPICON_APIMAP_JSON_PATH_PLACEHOLDER'); ?>',
            yoothemeNameLabel: '<?= Text::_('COM_VMMAPICON_APIMAP_YOOTHEME_NAME'); ?>',
            yoothemeNamePlaceholder: '<?= Text::_('COM_VMMAPICON_APIMAP_YOOTHEME_NAME_PLACEHOLDER'); ?>',
            typeLabel: '<?= Text::_('COM_VMMAPICON_APIMAP_TYPE'); ?>',
            fieldLabelLabel: '<?= Text::_('COM_VMMAPICON_APIMAP_FIELD_LABEL'); ?>',
            fieldLabelPlaceholder: '<?= Text::_('COM_VMMAPICON_APIMAP_FIELD_LABEL_PLACEHOLDER'); ?>',
            typeString: '<?= Text::_('COM_VMMAPICON_APIMAP_TYPE_STRING'); ?>',
            typeNumber: '<?= Text::_('COM_VMMAPICON_APIMAP_TYPE_NUMBER'); ?>',
            typeBoolean: '<?= Text::_('COM_VMMAPICON_APIMAP_TYPE_BOOLEAN'); ?>',
            typeArray: '<?= Text::_('COM_VMMAPICON_APIMAP_TYPE_ARRAY'); ?>',
            typeObject: '<?= Text::_('COM_VMMAPICON_APIMAP_TYPE_OBJECT'); ?>'
        }
    };

    if (window.ApiMapping && typeof window.ApiMapping.init === 'function') {
        window.ApiMapping.init();
    }
</script>
