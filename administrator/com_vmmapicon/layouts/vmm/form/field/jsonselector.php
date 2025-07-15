<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

// Script laden
try {
    $wa->useScript('com_vmmapicon.jsonselector');
} catch (Exception $e) {
    // Fallback: Direktes Laden
    Factory::getDocument()->addScript('media/com_vmmapicon/js/jsonField.js');
}

extract($displayData);

// JSON dekodieren
$decodedApi = [];
if (!empty($apiResult)) {
    $decodedApi = $apiResult;
    if (json_last_error() !== JSON_ERROR_NONE) {
        $decodedApi = [];
    }
}


$attributes = [
    !empty($class) ? 'class="form-control ' . $class . '"' : 'class="form-control"',
];
?>

<div class="input-group">
    <div class="container-fluid p-3">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div id="api-mapper">
                    <div class="text-center">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Lade API-Daten...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input
            type="hidden"
            name="<?php echo $name; ?>"
            id="<?php echo $id; ?>"
            value='<?= json_encode($decodedApi)?>'
        <?php echo implode(' ', $attributes); ?>
    >
</div>
