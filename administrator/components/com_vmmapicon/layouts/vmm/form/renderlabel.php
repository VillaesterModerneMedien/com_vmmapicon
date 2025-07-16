<?php
/**
 *
 *
 * \ \    / /  \/  |  \/  |
 *  \ \  / /| \  / | \  / |
 *   \ \/ / | |\/| | |\/| |
 *    \  /  | |  | | |  | |
 *     \/   |_|  |_|_|  |_| Villaester Moderne Medien GmbH
 *
 * @package     Joomla.Component
 * @subpackage  com_vmmapicon
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien
 * @author      Mario Hewera & Kiki Schuelling
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $text      The label text
 * @var   string   $for       The id of the input this label is for
 * @var   boolean  $required  True if a required field
 * @var   array    $classes   A list of classes
 */

$classes = array_filter((array) $classes);
$id      = $for . '-lbl';

if ($required) {
    $classes[] = 'required';
}

?>
<label id="<?php echo $id; ?>" for="<?php echo $for; ?>"<?php if (!empty($classes)) {
    echo ' class="' . implode(' ', $classes) . '"';
           } ?>>
    <?php echo $text; ?><?php if ($required) :
        ?><span class="star" aria-hidden="true">&#160;*</span><?php
    endif; ?>
</label>
