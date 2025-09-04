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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$params = ComponentHelper::getParams('com_vmmapicon');

?>
<div class="com-vmmapicon-apis apis-list<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->items)) : ?>
        <div class="apis-container">
            <?php foreach ($this->items as $item) : ?>
                <div class="api-item" itemscope itemtype="https://schema.org/WebAPI">
                    <h2 itemprop="name">
                        <a href="<?php echo Route::_('index.php?option=com_vmmapicon&view=api&id=' . $item->id); ?>" itemprop="url">
                            <?php echo $this->escape($item->title); ?>
                        </a>
                    </h2>

                    <?php if (!empty($item->description)) : ?>
                        <div class="api-description" itemprop="description">
                            <?php echo $item->description; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($item->api_url)) : ?>
                        <div class="api-url">
                            <strong><?php echo Text::_('COM_VMMAPICON_API_URL'); ?>:</strong>
                            <code><?php echo $this->escape($item->api_url); ?></code>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->params->get('show_create_date') && !empty($item->created)) : ?>
                        <div class="api-created">
                            <small><?php echo Text::_('COM_VMMAPICON_CREATED_DATE'); ?>: <?php echo HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC')); ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($this->pagination->pagesTotal > 1) : ?>
            <div class="pagination">
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="alert alert-info">
            <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo Text::_('COM_VMMAPICON_NO_APIS_FOUND'); ?>
        </div>
    <?php endif; ?>
</div>


