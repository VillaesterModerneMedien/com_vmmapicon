<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien GmbH
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

?>
<div class="com-vmmapicon-api api-item<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/WebAPI">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php if ($this->params->get('show_title')) : ?>
        <h2 itemprop="name">
            <?php if ($this->params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
                <a href="<?php echo $this->item->readmore_link; ?>" itemprop="url">
                    <?php echo $this->escape($this->item->title); ?>
                </a>
            <?php else : ?>
                <?php echo $this->escape($this->item->title); ?>
            <?php endif; ?>
        </h2>
    <?php endif; ?>

    <?php if ($this->params->get('show_intro')) : ?>
        <div class="api-intro" itemprop="description">
            <?php echo $this->item->description; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->item->api_url)) : ?>
        <div class="api-url">
            <strong><?php echo Text::_('COM_VMMAPICON_API_URL'); ?>:</strong>
            <code><?php echo $this->escape($this->item->api_url); ?></code>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->item->api_params)) : ?>
        <div class="api-params">
            <strong><?php echo Text::_('COM_VMMAPICON_API_PARAMS'); ?>:</strong>
            <pre><?php echo json_encode(json_decode($this->item->api_params), JSON_PRETTY_PRINT); ?></pre>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->item->api_method)) : ?>
        <div class="api-method">
            <strong><?php echo Text::_('COM_VMMAPICON_API_METHOD'); ?>:</strong>
            <code><?php echo $this->escape($this->item->api_method); ?></code>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->item->api_mapping)) : ?>
        <div class="api-mapping">
            <h3><?php echo Text::_('COM_VMMAPICON_FIELD_MAPPING_LABEL'); ?></h3>
            <pre><?php echo json_encode(json_decode($this->item->api_mapping), JSON_PRETTY_PRINT); ?></pre>
        </div>
    <?php endif; ?>

    <?php if ($this->params->get('show_create_date')) : ?>
        <div class="api-created">
            <strong><?php echo Text::_('COM_VMMAPICON_CREATED_DATE'); ?>:</strong>
            <?php echo HTMLHelper::_('date', $this->item->created, Text::_('DATE_FORMAT_LC')); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->params->get('show_modify_date') && !empty($this->item->modified)) : ?>
        <div class="api-modified">
            <strong><?php echo Text::_('COM_VMMAPICON_MODIFIED_DATE'); ?>:</strong>
            <?php echo HTMLHelper::_('date', $this->item->modified, Text::_('DATE_FORMAT_LC')); ?>
        </div>
    <?php endif; ?>
</div>
