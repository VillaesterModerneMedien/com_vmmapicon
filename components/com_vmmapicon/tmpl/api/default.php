<?php
/**
 * @package     Joomla.Component
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

// $this->item enthält die API-Antwort (typischerweise JSON-String)
$rawResponse = $this->item;

// Versuche JSON hübsch zu formatieren, falls es JSON ist
$formatted = null;
if (is_string($rawResponse)) {
    $decoded = json_decode($rawResponse, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $formatted = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

?>

<div class="com-vmmapicon api-view">
    <?php if ($formatted !== null) : ?>
        <pre class="api-response" style="white-space: pre-wrap; word-break: break-word;">
<?= htmlspecialchars($formatted, ENT_QUOTES, 'UTF-8'); ?>
        </pre>
    <?php else : ?>
        <pre class="api-response" style="white-space: pre-wrap; word-break: break-word;">
<?= htmlspecialchars(is_scalar($rawResponse) ? (string) $rawResponse : print_r($rawResponse, true), ENT_QUOTES, 'UTF-8'); ?>
        </pre>
    <?php endif; ?>
</div>



