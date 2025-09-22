<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// $this->item contains a single API item (array/object)
$data = $this->item;
?>

<div class="com-vmmapicon-apiitem<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Thing">
    <h1 class="uk-h3 uk-margin-small"><?php echo Text::_('COM_VMMAPICON_APIITEM_TITLE'); ?></h1>

    <?php if (!empty($this->mappingMeta)) : ?>
        <div class="uk-card uk-card-default uk-card-body uk-margin">
            <h3 class="uk-h5 uk-margin-remove"><?php echo Text::_('COM_VMMAPICON_APIITEM_MAPPED_FIELDS'); ?></h3>
            <dl class="uk-description-list uk-description-list-divider uk-margin-small-top">
                <?php foreach ($this->mappingMeta as $entry) :
                    $name = $entry['yootheme_name'];
                    $label = $entry['field_label'] ?: $name;
                    $value = $this->mapped[$name] ?? null;
                    $out = is_array($value) || is_object($value)
                        ? htmlspecialchars(json_encode($value, JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8')
                        : htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
                ?>
                    <dt><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></dt>
                    <dd><code><?php echo $out; ?></code></dd>
                <?php endforeach; ?>
            </dl>
        </div>
    <?php else : ?>
        <div class="uk-alert uk-alert-warning uk-margin">
            <?php echo Text::_('COM_VMMAPICON_APIITEM_NO_MAPPING'); ?>
        </div>
    <?php endif; ?>

    <details class="uk-margin">
        <summary class="uk-h6"><?php echo Text::_('COM_VMMAPICON_APIITEM_RAW_DATA'); ?></summary>
        <pre class="uk-text-small uk-text-break"><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8'); ?></pre>
    </details>
</div>
