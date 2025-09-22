<?php
/**
* @package      DigiNerds Vmmapicon24 Package
*
* @author       Christian Schuelling <info@diginerds.de>
* @copyright    2024 diginerds.de - All rights reserved.
* @license      GNU General Public License version 3 or later
*/

namespace Villaester\Component\Vmmapicon\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Multilanguage;

/**
 * Vmmapicon Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_vmmapicon * @since       1.0.0
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a api from a api ID, apis category ID and language
	 *
	 * @param   integer  $id        The id of the apis
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the apis
	 *
	 * @since   1.0.0
	 */
    public static function getApiRoute($id, $language = 0)
    {
		// Create the link
		$link = 'index.php?option=com_vmmapicon&view=api&apiID=' . $id;
		
		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

        return $link;
    }

    /**
     * Get the URL for a single API item view
     *
     * @param integer $id API configuration ID
     * @param integer $index Index within the result list
     * @param string $path Optional path within JSON (e.g. data->items)
     * @return string Link to apiitem
     */
    public static function getApiItemRoute($id, $index = 0, $path = '')
    {
        $link = 'index.php?option=com_vmmapicon&view=apiitem&id=' . (int) $id;
        if ($index) {
            $link .= '&index=' . (int) $index;
        }
        if ($path !== '') {
            $link .= '&path=' . rawurlencode($path);
        }
        return $link;
    }
}
