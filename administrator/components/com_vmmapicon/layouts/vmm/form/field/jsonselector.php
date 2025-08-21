<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

try {
	$wa->useScript('com_vmmapicon.jsonselector');
} catch (Exception $e) {
	Factory::getDocument()->addScript('media/com_vmmapicon/js/jsonField.js');
}

extract($displayData);

$decodedApi = [];
if (!empty($apiResult)) {
	$decodedApi = $apiResult;
}

// Bestehende Subform-Daten laden
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
                <h5 class="mb-0">JSON zu YooTheme Mapping</h5>
                <button type="button" class="btn btn-success btn-sm" onclick="JsonSubform.addRow()">
                    <i class="fas fa-plus"></i> Feld hinzufügen
                </button>
            </div>
            <div class="card-body">
                <div id="subform-rows" class="subform-rows">
                </div>
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
    window.jsonSubformConfig = {
        apiData: <?= json_encode($decodedApi) ?>,
        fieldName: '<?= $fieldName ?>',
        fieldId: '<?= $fieldId ?>',
        existingData: <?= json_encode($existingData) ?>,
        selectorsFieldName: 'jform[api_selectors]'
    };
</script>