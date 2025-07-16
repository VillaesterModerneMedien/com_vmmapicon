<?php
    /**
     *
     *
     * \ \    / /  \/  |  \/  |
     *  \ \  / /| \  / | \  / |
     *   \ \/ / | |\/| | |\/| |
     *    \  /  | |  | | |  | |
     *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH * * @package Joomla.Component
     * @subpackage  com_vmmapico
     * @copyright   Copyright (C) 2025 Villaester Moderne Medien
     * @author      Mario Hewera & Kiki Schuelling
     * @license     GNU General Public License version 2 or later
     * @author      VMM Development Team
     * @link        https://villaester.de
     * @version     1.0.0
     */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.multiselect');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo Route::_('index.php?option=com_vmmapicon&view=apis'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table" id="apiList">
                        <thead>
                            <tr>
                                <th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" style="width:1%" class="d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->items as $i => $item) : ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="text-center d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td>
                                    <a href="<?php echo Route::_('index.php?option=com_vmmapicon&task=api.edit&id=' . $item->id); ?>">
                                        <?php echo $this->escape($item->title); ?>
                                    </a>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'apis.', true); ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $item->id; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->getListFooter(); ?>
                <?php endif; ?>
                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
