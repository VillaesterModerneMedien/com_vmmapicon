<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_vmmapicon.jsonselector');

extract($displayData);

// JSON korrekt dekodieren (falls nötig), sonst leeres Array
$decodedApi = json_decode($apiResult, true);
if (json_last_error() !== JSON_ERROR_NONE) {
	$decodedApi = [];
}

// Übergabe an Joomla.getOptions('com_vmmapicon')
Factory::getDocument()->addScriptOptions('com_vmmapicon', [
	'apiData' => $decodedApi
]);

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

    <!-- optional: zum Debuggen im DOM -->
    <input type="hidden" name="apiResult" id="apiResult" value="<?= htmlspecialchars($apiResult, ENT_QUOTES, 'UTF-8'); ?>">

    <input
            type="hidden"
            name="<?php echo $name; ?>"
            id="<?php echo $id; ?>"
            value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>"
		<?php echo implode(' ', $attributes); ?>
    >
</div>