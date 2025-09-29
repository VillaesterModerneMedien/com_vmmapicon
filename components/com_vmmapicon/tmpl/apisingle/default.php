<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// $this->item contains a single API item (array/object)
$data = $this->item;
?>

<div class="com-vmmapicon-apiitem<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Thing">
	<h1 class="uk-h3 uk-margin-small"><?php echo Text::_('COM_VMMAPICON_APIITEM_TITLE'); ?></h1>

	<details class="uk-margin">
		<summary class="uk-h6"><?php echo Text::_('COM_VMMAPICON_APIITEM_RAW_DATA'); ?></summary>
		<pre class="uk-text-small uk-text-break"><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8'); ?></pre>
	</details>
</div>
