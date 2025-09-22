<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

try {
    // Prefer admin asset to avoid site media restrictions
    $wa->useScript('com_vmmapicon.apimapping_admin');
} catch (\Throwable $e) {
    // Fallback: register from admin component media path
    $wa->registerScript('com_vmmapicon.apimapping_admin', Uri::root() . 'administrator/components/com_vmmapicon/media/js/apiMapping.js?v=2', [], ['defer' => true]);
    $wa->useScript('com_vmmapicon.apimapping_admin');
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

<div class="apimapping-modebar d-flex gap-2 align-items-center mb-2">
    <strong><?= Text::_('COM_VMMAPICON_APIMAP_HEADING'); ?></strong>
    <div class="ms-auto d-flex gap-2">
        <button type="button" id="apimapping-mode-form" class="btn btn-outline-primary btn-sm"><?= Text::_('COM_VMMAPICON_APIMAP_MODE_FORM'); ?></button>
        <button type="button" id="apimapping-mode-editor" class="btn btn-outline-secondary btn-sm"><?= Text::_('COM_VMMAPICON_APIMAP_MODE_EDITOR'); ?></button>
        <button type="button" id="apimapping-add-row" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> <?= Text::_('COM_VMMAPICON_APIMAP_ADD_FIELD'); ?>
        </button>
    </div>
    <small id="apimapping-type-note" class="text-warning d-none ms-2"><?= Text::_('COM_VMMAPICON_APIMAP_TYPE_NOTE_JSON_MANUAL'); ?></small>
    <small class="text-muted d-block w-100 mt-1"><?= Text::_('COM_VMMAPICON_APIMAP_EDITOR_HINT'); ?></small>
    <hr class="w-100"/>
</div>

<div class="apimapping-editor-container mb-3" style="display:none;">
    <textarea id="apimapping-editor" class="form-control font-monospace" rows="14"
              placeholder="{\n  \"json_mapping0\": {\n    \"json_path\": \"data->attributes->title\",\n    \"yootheme_name\": \"title\",\n    \"field_type\": \"String\",\n    \"field_label\": \"Title\"\n  }\n}"></textarea>
</div>

<div class="subform-container" style="">
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-body">
                <div id="subform-rows" class="subform-rows"></div>
            </div>
        </div>
    </div>

    <input type="hidden" name="<?php echo $fieldName; ?>" id="<?php echo $fieldId; ?>"
           value='<?= htmlspecialchars($fieldValue ?: '{}'); ?>' <?php echo implode(' ', $attributes); ?>>
</div>

    

<script>
    window.apiMappingConfig = {
        apiData: <?= json_encode($decodedApi) ?>,
        fieldName: '<?= $fieldName ?>',
        fieldId: '<?= $fieldId ?>',
        existingData: <?= json_encode($existingData) ?>,
        selectorsFieldName: 'jform[api_selectors]'
    };

    if (window.ApiMapping && typeof window.ApiMapping.init === 'function') {
        window.ApiMapping.init();
    }
    // Ensure API type affects UI immediately (guard against asset caching)
    (function(){
        var typeSel = document.getElementById('jform_api_type');
        var btnForm = document.getElementById('apimapping-mode-form');
        var addBtn = document.getElementById('apimapping-add-row');
        var note = document.getElementById('apimapping-type-note');
        var subform = document.querySelector('.subform-container');
        var editor = document.querySelector('.apimapping-editor-container');
        function apply(){
            var isJson = !typeSel || typeSel.value === 'json';
            if (btnForm) btnForm.disabled = false;
            if (addBtn) addBtn.disabled = false;
            if (note) note.classList.toggle('d-none', isJson);
        }
        if (typeSel){ typeSel.addEventListener('change', apply); apply(); setTimeout(apply, 50); }
    })();
</script>
